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
		$attrs = [
			'name' => $name,
			'data' => self::prepareData($slug, $data),
			'mode' => 'edit',
		];

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
			return self::stripInternalKeys($data);
		}

		$encoded = self::encodeWithKeys($data, $fields);

		foreach ($data as $name => $value) {
			if (!is_string($name) || str_starts_with($name, '_')) {
				continue;
			}

			if (!array_key_exists($name, $encoded)) {
				$encoded[$name] = $value;
			}
		}

		return $encoded;
	}

	/**
	 * @return list<array<string, mixed>>
	 */
	private static function fieldsForBlock(string $slug): array
	{
		if (!function_exists('acf_get_field_groups') || !function_exists('acf_get_fields')) {
			return [];
		}

		$groups = acf_get_field_groups([
			'block' => 'acf/' . $slug,
		]);

		if (empty($groups[0])) {
			return [];
		}

		$fields = acf_get_fields($groups[0]['key'] ?? $groups[0]);

		return is_array($fields) ? $fields : [];
	}

	/**
	 * @param array<string, mixed> $data
	 * @param list<array<string, mixed>> $fields
	 * @return array<string, mixed>
	 */
	private static function encodeWithKeys(array $data, array $fields): array
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

			if (!array_key_exists($name, $data)) {
				continue;
			}

			$value = $data[$name];
			$subFields = is_array($field['sub_fields'] ?? null) ? $field['sub_fields'] : [];

			if ($type === 'group' && is_array($value) && $subFields !== []) {
				$out[$name] = self::encodeWithKeys($value, $subFields);
			} elseif ($type === 'repeater' && is_array($value) && $subFields !== []) {
				$rows = [];

				foreach (array_values($value) as $i => $row) {
					$rows[$i] = is_array($row) ? self::encodeWithKeys($row, $subFields) : $row;
				}

				$out[$name] = $rows;
			} else {
				$out[$name] = $value;
			}

			if (!empty($field['key'])) {
				$out['_' . $name] = $field['key'];
			}
		}

		return $out;
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
