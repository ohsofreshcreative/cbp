<?php

namespace App\Support;

class PageImportAssets
{
	/** @var list<string> */
	private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

	private ?string $sourceDir;

	/** @var array<string, int> realpath => attachment ID */
	private array $imported = [];

	/** @var list<int> */
	private array $created = [];

	public function __construct(?string $sourceDir = null)
	{
		$this->sourceDir = $sourceDir !== null && $sourceDir !== ''
			? rtrim($sourceDir, '/\\')
			: null;
	}

	/**
	 * Zamienia obiekty {src, alt} w drzewie danych na ID załączników WP.
	 *
	 * @param array<string, mixed> $data
	 * @return array<string, mixed>
	 */
	public function hydrate(array $data, string $label = 'data'): array
	{
		$missing = [];
		$invalid = [];
		$assets = [];

		$this->collect($data, $label, $assets, $missing, $invalid);

		if ($missing !== [] || $invalid !== []) {
			$lines = array_merge($invalid, $missing);
			throw new PageImportException(
				"Nie można zaimportować obrazów:\n- " . implode("\n- ", $lines)
			);
		}

		if ($assets === []) {
			return $data;
		}

		if (!$this->canImport()) {
			throw new PageImportException('Import obrazów wymaga WordPress (wp_insert_attachment).');
		}

		$this->imported = [];
		$this->created = [];

		try {
			/** @var array<string, mixed> $replaced */
			$replaced = $this->replace($data);
			return $replaced;
		} catch (PageImportException $e) {
			$this->rollback();
			throw $e;
		} catch (\Throwable $e) {
			$this->rollback();
			throw new PageImportException('Nie udało się zaimportować obrazu: ' . $e->getMessage(), 0, $e);
		}
	}

	public static function isAsset(mixed $value): bool
	{
		if (!is_array($value) || array_is_list($value) || !array_key_exists('src', $value)) {
			return false;
		}

		return true;
	}

	/**
	 * @param array<string, mixed>|list<mixed> $node
	 * @param list<array{label: string, src: string, alt: string, file: string}> $assets
	 * @param list<string> $missing
	 * @param list<string> $invalid
	 */
	private function collect(array $node, string $label, array &$assets, array &$missing, array &$invalid): void
	{
		if (self::isAsset($node)) {
			$src = $node['src'] ?? null;

			if (!is_string($src) || trim($src) === '') {
				$invalid[] = sprintf('%s: pole "src" musi być niepustą ścieżką pliku.', $label);
				return;
			}

			$src = trim($src);

			if ($this->isRemoteUrl($src)) {
				$invalid[] = sprintf('%s: "src" musi być lokalną ścieżką, nie URL-em (%s).', $label, $src);
				return;
			}

			$file = $this->resolveFile($src);

			if ($file === null) {
				$missing[] = sprintf('%s: nie znaleziono pliku "%s".', $label, $src);
				return;
			}

			$error = $this->validateImage($file);
			if ($error !== null) {
				$invalid[] = sprintf('%s: %s (%s).', $label, $error, $src);
				return;
			}

			$alt = $node['alt'] ?? '';
			if ($alt !== null && !is_string($alt) && !is_numeric($alt)) {
				$invalid[] = sprintf('%s: pole "alt" musi być stringiem.', $label);
				return;
			}

			$assets[] = [
				'label' => $label,
				'src' => $src,
				'alt' => is_string($alt) ? $alt : (string) $alt,
				'file' => $file,
			];
			return;
		}

		foreach ($node as $key => $child) {
			if (!is_array($child)) {
				continue;
			}

			$childLabel = is_int($key) || (is_string($key) && ctype_digit($key))
				? sprintf('%s[%s]', $label, $key)
				: sprintf('%s.%s', $label, $key);

			$this->collect($child, $childLabel, $assets, $missing, $invalid);
		}
	}

	private function replace(mixed $node): mixed
	{
		if (is_array($node) && self::isAsset($node)) {
			$src = trim((string) $node['src']);
			$alt = isset($node['alt']) && (is_string($node['alt']) || is_numeric($node['alt']))
				? (string) $node['alt']
				: '';
			$file = $this->resolveFile($src);

			if ($file === null) {
				throw new PageImportException(sprintf('Nie znaleziono pliku obrazu: %s', $src));
			}

			return $this->importFile($file, $alt);
		}

		if (!is_array($node)) {
			return $node;
		}

		$out = [];

		foreach ($node as $key => $child) {
			$out[$key] = $this->replace($child);
		}

		return $out;
	}

	private function importFile(string $file, string $alt): int
	{
		if (isset($this->imported[$file])) {
			return $this->imported[$file];
		}

		$this->ensureMediaApi();

		$bits = file_get_contents($file);

		if ($bits === false) {
			throw new PageImportException(sprintf('Nie można odczytać pliku obrazu: %s', $file));
		}

		$filename = basename($file);

		if (function_exists('sanitize_file_name')) {
			$filename = sanitize_file_name($filename);
		}

		if (!function_exists('wp_upload_bits') || !function_exists('wp_insert_attachment')) {
			throw new PageImportException('Import obrazów wymaga WordPress (wp_insert_attachment).');
		}

		$upload = wp_upload_bits($filename, null, $bits);

		if (!is_array($upload) || !empty($upload['error']) || empty($upload['file'])) {
			$error = is_array($upload) && !empty($upload['error']) ? (string) $upload['error'] : 'nieznany błąd';
			throw new PageImportException(sprintf('Nie udało się wgrać obrazu "%s": %s', $filename, $error));
		}

		$filetype = function_exists('wp_check_filetype')
			? wp_check_filetype($upload['file'])
			: ['type' => $upload['type'] ?? 'image/jpeg'];

		$title = pathinfo($filename, PATHINFO_FILENAME);
		if (function_exists('sanitize_text_field')) {
			$title = sanitize_text_field($title);
		}

		$attachment = [
			'post_mime_type' => $filetype['type'] ?? ($upload['type'] ?? 'image/jpeg'),
			'post_title' => $title,
			'post_content' => '',
			'post_status' => 'inherit',
		];

		$id = wp_insert_attachment($attachment, $upload['file'], 0, true);

		if (function_exists('is_wp_error') && is_wp_error($id)) {
			throw new PageImportException(sprintf(
				'Nie udało się utworzyć załącznika dla "%s": %s',
				$filename,
				$id->get_error_message()
			));
		}

		if (is_object($id) && method_exists($id, 'get_error_message')) {
			throw new PageImportException(sprintf(
				'Nie udało się utworzyć załącznika dla "%s": %s',
				$filename,
				$id->get_error_message()
			));
		}

		$id = (int) $id;

		if ($id <= 0) {
			throw new PageImportException(sprintf('WordPress nie zwrócił ID załącznika dla "%s".', $filename));
		}

		$this->created[] = $id;

		if (function_exists('wp_generate_attachment_metadata') && function_exists('wp_update_attachment_metadata')) {
			$metadata = wp_generate_attachment_metadata($id, $upload['file']);
			if (is_array($metadata)) {
				wp_update_attachment_metadata($id, $metadata);
			}
		}

		if ($alt !== '' && function_exists('update_post_meta')) {
			update_post_meta($id, '_wp_attachment_image_alt', $this->sanitizeAlt($alt));
		}

		$this->imported[$file] = $id;

		return $id;
	}

	public function resolveFile(string $src): ?string
	{
		$src = trim($src);

		if ($src === '') {
			return null;
		}

		$candidates = [];

		if ($this->isAbsolutePath($src)) {
			$candidates[] = $src;
		} else {
			if ($this->sourceDir !== null) {
				$candidates[] = $this->sourceDir . DIRECTORY_SEPARATOR . $src;
			}

			$cwd = getcwd();
			if (is_string($cwd) && $cwd !== '') {
				$candidates[] = $cwd . DIRECTORY_SEPARATOR . $src;
			}

			if (function_exists('get_theme_file_path')) {
				$candidates[] = get_theme_file_path($src);
			}

			$themeRoot = dirname(__DIR__, 2);
			$candidates[] = $themeRoot . DIRECTORY_SEPARATOR . $src;
		}

		foreach ($candidates as $candidate) {
			if (!is_string($candidate) || $candidate === '') {
				continue;
			}

			$real = realpath($candidate);

			if ($real !== false && is_file($real) && is_readable($real)) {
				return $real;
			}
		}

		return null;
	}

	private function validateImage(string $file): ?string
	{
		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		if (!in_array($ext, self::IMAGE_EXTENSIONS, true)) {
			return sprintf('plik musi być obrazem (%s)', implode(', ', self::IMAGE_EXTENSIONS));
		}

		$info = @getimagesize($file);

		if ($info === false) {
			return 'plik nie jest poprawnym obrazem';
		}

		return null;
	}

	private function isRemoteUrl(string $src): bool
	{
		return (bool) preg_match('#^(https?:)?//#i', $src);
	}

	private function isAbsolutePath(string $path): bool
	{
		return str_starts_with($path, '/') || (strlen($path) > 2 && ctype_alpha($path[0]) && $path[1] === ':');
	}

	private function canImport(): bool
	{
		return function_exists('wp_insert_attachment') && function_exists('wp_upload_bits');
	}

	private function ensureMediaApi(): void
	{
		if (!defined('ABSPATH')) {
			return;
		}

		foreach (['file.php', 'media.php', 'image.php'] as $relative) {
			$path = ABSPATH . 'wp-admin/includes/' . $relative;

			if (is_readable($path)) {
				require_once $path;
			}
		}
	}

	private function sanitizeAlt(string $alt): string
	{
		if (function_exists('sanitize_text_field')) {
			return sanitize_text_field($alt);
		}

		return trim($alt);
	}

	private function rollback(): void
	{
		if (!function_exists('wp_delete_attachment')) {
			$this->created = [];
			return;
		}

		foreach ($this->created as $id) {
			wp_delete_attachment($id, true);
		}

		$this->created = [];
		$this->imported = [];
	}
}
