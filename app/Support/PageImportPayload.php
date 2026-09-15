<?php

namespace App\Support;

class PageImportPayload
{
	public const STATUSES = ['draft', 'publish', 'pending', 'private'];

	public string $title;

	public string $slug;

	public string $status;

	/** @var list<array{block: string, data: array<string, mixed>}> */
	public array $blocks;

	private function __construct(string $title, string $slug, string $status, array $blocks)
	{
		$this->title = $title;
		$this->slug = $slug;
		$this->status = $status;
		$this->blocks = $blocks;
	}

	public static function fromFile(string $path): self
	{
		if ($path === '') {
			throw new PageImportException('Podaj ścieżkę do pliku JSON.');
		}

		if (!is_readable($path)) {
			throw new PageImportException(sprintf('Nie można odczytać pliku JSON: %s', $path));
		}

		$raw = file_get_contents($path);

		if ($raw === false) {
			throw new PageImportException(sprintf('Nie można odczytać pliku JSON: %s', $path));
		}

		return self::fromJson($raw, $path);
	}

	public static function fromJson(string $json, string $source = 'JSON'): self
	{
		try {
			$decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
		} catch (\JsonException $e) {
			throw new PageImportException(sprintf('Niepoprawny JSON (%s): %s', $source, $e->getMessage()));
		}

		if (!is_array($decoded) || self::isList($decoded)) {
			throw new PageImportException('JSON musi być obiektem z polami title i blocks.');
		}

		return self::fromArray($decoded);
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public static function fromArray(array $data): self
	{
		if (!array_key_exists('title', $data) || !is_string($data['title']) || trim($data['title']) === '') {
			throw new PageImportException('Pole "title" jest wymagane i musi być niepustym stringiem.');
		}

		$title = trim($data['title']);

		$slug = $data['slug'] ?? null;

		if ($slug === null || $slug === '') {
			$slug = self::slugify($title);
		}

		if (!is_string($slug) || trim($slug) === '') {
			throw new PageImportException('Pole "slug" musi być niepustym stringiem.');
		}

		$slug = self::slugify(trim($slug));

		if ($slug === '') {
			throw new PageImportException('Nie udało się zbudować sluga strony.');
		}

		$status = $data['status'] ?? 'draft';

		if (!is_string($status) || $status === '') {
			throw new PageImportException('Pole "status" musi być stringiem.');
		}

		$status = strtolower($status);

		if (!in_array($status, self::STATUSES, true)) {
			throw new PageImportException(sprintf(
				'Nieobsługiwany status "%s". Dozwolone: %s.',
				$status,
				implode(', ', self::STATUSES)
			));
		}

		if (!array_key_exists('blocks', $data)) {
			throw new PageImportException('Pole "blocks" jest wymagane i musi być tablicą.');
		}

		if (!is_array($data['blocks']) || !self::isList($data['blocks'])) {
			throw new PageImportException('Pole "blocks" musi być tablicą bloków.');
		}

		$blocks = [];

		foreach ($data['blocks'] as $index => $item) {
			$blocks[] = self::normalizeBlock($item, (int) $index);
		}

		return new self($title, $slug, $status, $blocks);
	}

	/**
	 * @return array{block: string, data: array<string, mixed>}
	 */
	private static function normalizeBlock(mixed $item, int $index): array
	{
		$label = sprintf('blocks[%d]', $index);

		if (!is_array($item) || self::isList($item)) {
			throw new PageImportException(sprintf('%s musi być obiektem z polami "block" i "data".', $label));
		}

		if (!isset($item['block']) || !is_string($item['block']) || trim($item['block']) === '') {
			throw new PageImportException(sprintf('%s.block jest wymagane i musi być slugiem ACF (np. hero).', $label));
		}

		$slug = strtolower(trim($item['block']));

		if (!preg_match('/^[a-z][a-z0-9]*$/', $slug)) {
			throw new PageImportException(sprintf(
				'%s.block "%s" jest niepoprawny. Slug bloku to jedno słowo, lowercase, bez myślników.',
				$label,
				$item['block']
			));
		}

		self::assertKnownBlock($slug, $label);

		$data = $item['data'] ?? [];

		if ($data === null) {
			$data = [];
		}

		if (!is_array($data) || ($data !== [] && self::isList($data))) {
			throw new PageImportException(sprintf('%s.data musi być obiektem z danymi ACF.', $label));
		}

		/** @var array<string, mixed> $data */
		return [
			'block' => $slug,
			'data' => $data,
		];
	}

	private static function assertKnownBlock(string $slug, string $label): void
	{
		$studly = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $slug)));
		$file = dirname(__DIR__) . '/Blocks/' . $studly . '.php';

		if (!is_readable($file)) {
			throw new PageImportException(sprintf(
				'%s.block "%s" nie istnieje w app/Blocks. Oczekiwano pliku %s.',
				$label,
				$slug,
				'app/Blocks/' . $studly . '.php'
			));
		}

		$contents = file_get_contents($file);

		if ($contents !== false && !preg_match('/public\s+\$slug\s*=\s*[\'"]' . preg_quote($slug, '/') . '[\'"]/', $contents)) {
			throw new PageImportException(sprintf(
				'%s.block "%s" nie zgadza się z $slug w %s.',
				$label,
				$slug,
				'app/Blocks/' . $studly . '.php'
			));
		}
	}

	private static function slugify(string $value): string
	{
		if (function_exists('sanitize_title')) {
			return sanitize_title($value);
		}

		$value = strtolower($value);
		$value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

		return trim($value, '-');
	}

	private static function isList(array $value): bool
	{
		if (function_exists('array_is_list')) {
			return array_is_list($value);
		}

		return $value === [] || array_keys($value) === range(0, count($value) - 1);
	}
}
