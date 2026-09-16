<?php

namespace App\Support;

class PostImportPayload
{
	public const STATUSES = ['draft', 'publish', 'pending', 'private'];

	public string $title;

	public string $slug;

	public string $status;

	public string $content;

	public string $excerpt;

	public ?string $date;

	public ?string $author;

	/** @var list<string> */
	public array $categories;

	/** @var array{src: string, alt: string}|null */
	public ?array $featuredImage;

	/** @var list<array{id: string, block: string, data: array<string, mixed>}> */
	public array $embeds;

	public ?string $sourceDir;

	/**
	 * @param list<string> $categories
	 * @param array{src: string, alt: string}|null $featuredImage
	 * @param list<array{id: string, block: string, data: array<string, mixed>}> $embeds
	 */
	private function __construct(
		string $title,
		string $slug,
		string $status,
		string $content,
		string $excerpt,
		?string $date,
		?string $author,
		array $categories,
		?array $featuredImage,
		array $embeds = [],
		?string $sourceDir = null
	) {
		$this->title = $title;
		$this->slug = $slug;
		$this->status = $status;
		$this->content = $content;
		$this->excerpt = $excerpt;
		$this->date = $date;
		$this->author = $author;
		$this->categories = $categories;
		$this->featuredImage = $featuredImage;
		$this->embeds = $embeds;
		$this->sourceDir = $sourceDir;
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

		return self::fromJson($raw, $path, dirname($path));
	}

	public static function fromJson(string $json, string $source = 'JSON', ?string $sourceDir = null): self
	{
		try {
			$decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
		} catch (\JsonException $e) {
			throw new PageImportException(sprintf('Niepoprawny JSON (%s): %s', $source, $e->getMessage()));
		}

		if (!is_array($decoded) || self::isList($decoded)) {
			throw new PageImportException('JSON musi być obiektem z polami title i content.');
		}

		return self::fromArray($decoded, $sourceDir);
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public static function fromArray(array $data, ?string $sourceDir = null): self
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
			throw new PageImportException('Nie udało się zbudować sluga wpisu.');
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

		$content = self::resolveContent($data, $sourceDir);

		$excerpt = $data['excerpt'] ?? '';

		if ($excerpt !== null && !is_string($excerpt) && !is_numeric($excerpt)) {
			throw new PageImportException('Pole "excerpt" musi być stringiem.');
		}

		$excerpt = is_string($excerpt) ? $excerpt : (string) $excerpt;

		$date = self::normalizeDate($data['date'] ?? null);
		$author = self::normalizeOptionalString($data['author'] ?? null, 'author');
		$categories = self::normalizeCategories($data['categories'] ?? []);
		$featuredImage = self::normalizeFeaturedImage($data['featured_image'] ?? null);
		$embeds = self::normalizeEmbeds($data['embeds'] ?? []);

		return new self(
			$title,
			$slug,
			$status,
			$content,
			$excerpt,
			$date,
			$author,
			$categories,
			$featuredImage,
			$embeds,
			$sourceDir
		);
	}

	/**
	 * @param array<string, mixed> $data
	 */
	private static function resolveContent(array $data, ?string $sourceDir): string
	{
		$hasContent = array_key_exists('content', $data) && $data['content'] !== null && $data['content'] !== '';
		$hasFile = array_key_exists('content_file', $data) && $data['content_file'] !== null && $data['content_file'] !== '';

		if (!$hasContent && !$hasFile) {
			throw new PageImportException('Pole "content" albo "content_file" jest wymagane.');
		}

		if ($hasContent) {
			if (!is_string($data['content'])) {
				throw new PageImportException('Pole "content" musi być stringiem HTML.');
			}

			$content = trim($data['content']);

			if ($content === '') {
				throw new PageImportException('Pole "content" nie może być puste.');
			}

			return $content;
		}

		if (!is_string($data['content_file']) || trim($data['content_file']) === '') {
			throw new PageImportException('Pole "content_file" musi być niepustą ścieżką.');
		}

		$file = trim($data['content_file']);

		$candidates = [];

		if (str_starts_with($file, '/') || (strlen($file) > 2 && ctype_alpha($file[0]) && $file[1] === ':')) {
			$candidates[] = $file;
		} else {
			if ($sourceDir !== null) {
				$candidates[] = $sourceDir . DIRECTORY_SEPARATOR . $file;
				$candidates[] = $sourceDir . DIRECTORY_SEPARATOR . basename($file);
			}

			$cwd = getcwd();
			if (is_string($cwd) && $cwd !== '') {
				$candidates[] = $cwd . DIRECTORY_SEPARATOR . $file;
			}

			$themeRoot = dirname(__DIR__, 2);
			$candidates[] = $themeRoot . DIRECTORY_SEPARATOR . $file;
		}

		foreach ($candidates as $candidate) {
			$real = realpath($candidate);

			if ($real !== false && is_file($real) && is_readable($real)) {
				$html = file_get_contents($real);

				if ($html === false) {
					throw new PageImportException(sprintf('Nie można odczytać pliku treści: %s', $file));
				}

				$html = trim($html);

				if ($html === '') {
					throw new PageImportException(sprintf('Plik treści jest pusty: %s', $file));
				}

				return $html;
			}
		}

		throw new PageImportException(sprintf('Nie znaleziono pliku treści: %s', $file));
	}

	private static function normalizeDate(mixed $date): ?string
	{
		if ($date === null || $date === '') {
			return null;
		}

		if (!is_string($date) && !is_numeric($date)) {
			throw new PageImportException('Pole "date" musi być stringiem (Y-m-d albo Y-m-d H:i:s).');
		}

		$value = trim((string) $date);

		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
			return $value . ' 09:00:00';
		}

		if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
			return $value;
		}

		throw new PageImportException('Pole "date" musi mieć format Y-m-d albo Y-m-d H:i:s.');
	}

	private static function normalizeOptionalString(mixed $value, string $field): ?string
	{
		if ($value === null || $value === '') {
			return null;
		}

		if (!is_string($value) && !is_numeric($value)) {
			throw new PageImportException(sprintf('Pole "%s" musi być stringiem.', $field));
		}

		$trimmed = trim((string) $value);

		return $trimmed === '' ? null : $trimmed;
	}

	/**
	 * @return list<string>
	 */
	private static function normalizeCategories(mixed $categories): array
	{
		if ($categories === null || $categories === '') {
			return [];
		}

		if (is_string($categories)) {
			$categories = [$categories];
		}

		if (!is_array($categories) || !self::isList($categories)) {
			throw new PageImportException('Pole "categories" musi być tablicą nazw.');
		}

		$out = [];

		foreach ($categories as $index => $name) {
			if (!is_string($name) || trim($name) === '') {
				throw new PageImportException(sprintf('categories[%d] musi być niepustym stringiem.', $index));
			}

			$out[] = trim($name);
		}

		return $out;
	}

	/**
	 * @return array{src: string, alt: string}|null
	 */
	private static function normalizeFeaturedImage(mixed $image): ?array
	{
		if ($image === null || $image === '') {
			return null;
		}

		if (!is_array($image) || self::isList($image)) {
			throw new PageImportException('Pole "featured_image" musi być obiektem {src, alt}.');
		}

		$src = $image['src'] ?? null;

		if (!is_string($src) || trim($src) === '') {
			throw new PageImportException('featured_image.src musi być niepustą ścieżką pliku.');
		}

		$alt = $image['alt'] ?? '';

		if ($alt !== null && !is_string($alt) && !is_numeric($alt)) {
			throw new PageImportException('featured_image.alt musi być stringiem.');
		}

		return [
			'src' => trim($src),
			'alt' => is_string($alt) ? $alt : (string) $alt,
		];
	}

	/**
	 * @return list<array{id: string, block: string, data: array<string, mixed>}>
	 */
	private static function normalizeEmbeds(mixed $embeds): array
	{
		if ($embeds === null || $embeds === []) {
			return [];
		}

		if (!is_array($embeds) || !self::isList($embeds)) {
			throw new PageImportException('Pole "embeds" musi być tablicą bloków ACF do wstawienia w treść.');
		}

		$out = [];

		foreach ($embeds as $index => $item) {
			$label = sprintf('embeds[%d]', $index);

			if (!is_array($item) || self::isList($item)) {
				throw new PageImportException(sprintf('%s musi być obiektem z polami "id", "block" i "data".', $label));
			}

			$id = $item['id'] ?? null;

			if (!is_string($id) || trim($id) === '') {
				throw new PageImportException(sprintf('%s.id jest wymagane i musi być stringiem (np. action).', $label));
			}

			$id = strtolower(trim($id));

			if (!preg_match('/^[a-z][a-z0-9]*$/', $id)) {
				throw new PageImportException(sprintf('%s.id "%s" jest niepoprawne.', $label, $item['id']));
			}

			$slug = $item['block'] ?? null;

			if (!is_string($slug) || trim($slug) === '') {
				throw new PageImportException(sprintf('%s.block jest wymagane i musi być slugiem ACF (np. action).', $label));
			}

			$slug = strtolower(trim($slug));

			if (!preg_match('/^[a-z][a-z0-9]*$/', $slug)) {
				throw new PageImportException(sprintf('%s.block "%s" jest niepoprawny.', $label, $item['block']));
			}

			$studly = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $slug)));
			$file = dirname(__DIR__) . '/Blocks/' . $studly . '.php';

			if (!is_readable($file)) {
				throw new PageImportException(sprintf(
					'%s.block "%s" nie istnieje w app/Blocks.',
					$label,
					$slug
				));
			}

			$data = $item['data'] ?? [];

			if ($data === null) {
				$data = [];
			}

			if (!is_array($data) || ($data !== [] && self::isList($data))) {
				throw new PageImportException(sprintf('%s.data musi być obiektem z danymi ACF.', $label));
			}

			$out[] = [
				'id' => $id,
				'block' => $slug,
				'data' => $data,
			];
		}

		return $out;
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
