<?php

namespace App\Support;

class AcfBlockSerializer
{
	/**
	 * @param list<array{block: string, data: array<string, mixed>}> $blocks
	 */
	public static function toPostContent(array $blocks): string
	{
		$markup = [];

		foreach ($blocks as $item) {
			$markup[] = self::serializeBlock($item['block'], $item['data']);
		}

		return implode("\n\n", $markup);
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public static function serializeBlock(string $slug, array $data): string
	{
		$name = 'acf/' . $slug;
		$prepared = self::prepareData($slug, $data);
		$attrs = [
			'name' => $name,
			'data' => $prepared,
			'mode' => 'edit',
		];

		$attrs['id'] = self::blockId($attrs);

		if (function_exists('serialize_block')) {
			return serialize_block([
				'blockName' => $name,
				'attrs' => $attrs,
				'innerBlocks' => [],
				'innerHTML' => '',
				'innerContent' => [],
			]);
		}

		return sprintf(
			'<!-- wp:%s %s /-->',
			$name,
			self::encodeAttributes($attrs)
		);
	}

	/**
	 * @param array<string, mixed> $data
	 * @return array<string, mixed>
	 */
	public static function prepareData(string $slug, array $data): array
	{
		$fields = self::fieldsForBlock($slug);

		if ($fields === []) {
			if (function_exists('acf_get_field_groups')) {
				throw new PageImportException(sprintf(
					'Nie znaleziono grupy pól ACF dla bloku acf/%s. Sprawdź, czy ACF Composer zarejestrował blok (wp acorn acf:cache).',
					$slug
				));
			}

			return self::stripInternalKeys($data);
		}

		return self::encodeMeta($data, $fields);
	}

	/**
	 * @return list<array<string, mixed>>
	 */
	private static function fieldsForBlock(string $slug): array
	{
		$groups = self::groupsForBlock($slug);
		$fields = [];

		foreach ($groups as $group) {
			if (!function_exists('acf_get_fields')) {
				break;
			}

			$found = acf_get_fields($group['key'] ?? $group);
			if (is_array($found)) {
				$fields = array_merge($fields, $found);
			}
		}

		if ($fields === []) {
			$fields = self::fieldsFromComposer($slug);
		}

		return $fields;
	}

	/**
	 * @return list<array<string, mixed>>
	 */
	private static function groupsForBlock(string $slug): array
	{
		$name = 'acf/' . $slug;
		$matched = [];

		if (function_exists('acf_get_field_groups')) {
			$filtered = acf_get_field_groups([
				'block' => $name,
			]);

			if (is_array($filtered)) {
				foreach ($filtered as $group) {
					if (is_array($group)) {
						$matched[] = $group;
					}
				}
			}

			if ($matched === []) {
				$all = acf_get_field_groups();
				if (is_array($all)) {
					foreach ($all as $group) {
						if (is_array($group) && self::groupTargetsBlock($group, $name)) {
							$matched[] = $group;
						}
					}
				}
			}
		}

		if ($matched === [] && function_exists('acf_get_local_field_groups')) {
			$local = acf_get_local_field_groups();
			if (is_array($local)) {
				foreach ($local as $group) {
					if (is_array($group) && self::groupTargetsBlock($group, $name)) {
						$matched[] = $group;
					}
				}
			}
		}

		return $matched;
	}

	/**
	 * @param array<string, mixed> $group
	 */
	private static function groupTargetsBlock(array $group, string $name): bool
	{
		foreach ($group['location'] ?? [] as $orGroup) {
			if (!is_array($orGroup)) {
				continue;
			}

			foreach ($orGroup as $rule) {
				if (!is_array($rule)) {
					continue;
				}

				if (($rule['param'] ?? '') === 'block' && ($rule['value'] ?? '') === $name) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @return list<array<string, mixed>>
	 */
	private static function fieldsFromComposer(string $slug): array
	{
		$studly = str_replace(' ', '', ucwords($slug));
		$class = 'App\\Blocks\\' . $studly;

		if (!class_exists($class) || !function_exists('app')) {
			return [];
		}

		try {
			$block = self::makeComposerBlock($class);

			if (!is_object($block) || !method_exists($block, 'fields')) {
				return [];
			}

			$builder = $block->fields();

			if (!is_object($builder) || !method_exists($builder, 'build')) {
				return [];
			}

			$built = $builder->build();
			$fields = is_array($built) ? ($built['fields'] ?? []) : [];

			return is_array($fields) ? $fields : [];
		} catch (\Throwable $e) {
			return [];
		}
	}

	private static function makeComposerBlock(string $class): ?object
	{
		try {
			$resolved = app($class);
			if (is_object($resolved)) {
				return $resolved;
			}
		} catch (\Throwable $e) {
			// Block nie jest zbindowany w kontenerze — składamy ręcznie.
		}

		try {
			$composerClass = '\\Log1x\\AcfComposer\\AcfComposer';
			if (class_exists($composerClass)) {
				return new $class(app($composerClass));
			}
		} catch (\Throwable $e) {
			return null;
		}

		return null;
	}

	/**
	 * Zapisany format meta ACF: spłaszczone nazwy, wskaźniki _name oraz klucze field_* (v3).
	 *
	 * @param array<string, mixed> $data
	 * @param list<array<string, mixed>> $fields
	 * @return array<string, mixed>
	 */
	private static function encodeMeta(array $data, array $fields, string $prefix = ''): array
	{
		$out = [];

		foreach ($fields as $field) {
			if (!is_array($field) || empty($field['name'])) {
				continue;
			}

			$type = $field['type'] ?? '';

			if (in_array($type, ['tab', 'message', 'accordion'], true)) {
				continue;
			}

			$name = (string) $field['name'];
			$fullName = $prefix === '' ? $name : $prefix . '_' . $name;

			if (!array_key_exists($name, $data)) {
				continue;
			}

			$value = $data[$name];
			$subFields = is_array($field['sub_fields'] ?? null) ? $field['sub_fields'] : [];
			$key = (string) ($field['key'] ?? '');

			if ($type === 'group' && is_array($value) && $subFields !== []) {
				$out[$fullName] = '';
				if ($key !== '') {
					$out['_' . $fullName] = $key;
					$out[$key] = '';
				}
				$out += self::encodeMeta($value, $subFields, $fullName);
			} elseif ($type === 'repeater' && is_array($value) && $subFields !== []) {
				$rows = array_values($value);
				$out[$fullName] = count($rows);
				if ($key !== '') {
					$out['_' . $fullName] = $key;
					$out[$key] = count($rows);
				}

				foreach ($rows as $i => $row) {
					if (!is_array($row)) {
						continue;
					}

					$out += self::encodeMeta($row, $subFields, $fullName . '_' . $i);
				}
			} else {
				$normalized = self::normalizeValue($type, $value);
				$out[$fullName] = $normalized;
				if ($key !== '') {
					$out['_' . $fullName] = $key;
					$out[$key] = $normalized;
				}
			}
		}

		return $out;
	}

	private static function normalizeValue(string $type, mixed $value): mixed
	{
		if ($type === 'true_false') {
			return $value ? 1 : 0;
		}

		return $value;
	}

	/**
	 * @param array<string, mixed> $data
	 * @return array<string, mixed>
	 */
	private static function stripInternalKeys(array $data): array
	{
		$out = [];

		foreach ($data as $name => $value) {
			if (is_string($name) && str_starts_with($name, '_')) {
				continue;
			}

			$out[$name] = $value;
		}

		return $out;
	}

	/**
	 * @param array<string, mixed> $attrs
	 */
	private static function blockId(array $attrs): string
	{
		if (function_exists('acf_get_block_id') && function_exists('acf_ensure_block_id_prefix')) {
			return acf_ensure_block_id_prefix(acf_get_block_id($attrs));
		}

		return 'block_' . substr(sha1((string) ($attrs['name'] ?? '') . serialize($attrs['data'] ?? [])), 0, 13);
	}

	/**
	 * @param array<string, mixed> $attrs
	 */
	private static function encodeAttributes(array $attrs): string
	{
		$flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

		if (defined('JSON_THROW_ON_ERROR')) {
			$flags |= JSON_THROW_ON_ERROR;
		}

		$encoded = function_exists('wp_json_encode')
			? wp_json_encode($attrs, $flags)
			: json_encode($attrs, $flags);

		if (!is_string($encoded)) {
			throw new PageImportException('Nie udało się zserializować atrybutów bloku ACF.');
		}

		$encoded = preg_replace('/--/', '\\u002d\\u002d', $encoded) ?? $encoded;
		$encoded = preg_replace('/</', '\\u003c', $encoded) ?? $encoded;
		$encoded = preg_replace('/>/', '\\u003e', $encoded) ?? $encoded;
		$encoded = preg_replace('/&/', '\\u0026', $encoded) ?? $encoded;
		$encoded = preg_replace('/\\\\"/', '\\u0022', $encoded) ?? $encoded;

		return $encoded;
	}
}
