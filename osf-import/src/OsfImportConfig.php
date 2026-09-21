<?php

namespace App\Support;

class OsfImportConfig
{
	/** @var array<string, mixed>|null */
	private static ?array $cached = null;

	public static function dir(): string
	{
		if (defined('OSF_IMPORT_DIR')) {
			return rtrim((string) OSF_IMPORT_DIR, '/\\');
		}

		return dirname(__DIR__);
	}

	public static function themeRoot(): string
	{
		$configured = self::load()['theme_root'] ?? null;

		if (is_string($configured) && trim($configured) !== '') {
			return rtrim($configured, '/\\');
		}

		if (function_exists('get_theme_file_path')) {
			$fromTheme = get_theme_file_path('');

			if (is_string($fromTheme) && $fromTheme !== '') {
				return rtrim($fromTheme, '/\\');
			}
		}

		return dirname(self::dir());
	}

	public static function appPath(): string
	{
		$configured = self::load()['app_path'] ?? null;

		if (is_string($configured) && trim($configured) !== '') {
			return rtrim($configured, '/\\');
		}

		return self::themeRoot() . '/app';
	}

	public static function blocksPath(): string
	{
		return self::appPath() . '/Blocks';
	}

	/**
	 * @return list<array{page: string, block: string, message: string}>
	 */
	public static function forbiddenBlocks(): array
	{
		$rules = self::load()['forbidden_blocks'] ?? [];

		if (!is_array($rules)) {
			return [];
		}

		$out = [];

		foreach ($rules as $rule) {
			if (!is_array($rule)) {
				continue;
			}

			$page = $rule['page'] ?? '';
			$block = $rule['block'] ?? '';
			$message = $rule['message'] ?? '';

			if (!is_string($page) || !is_string($block) || !is_string($message)) {
				continue;
			}

			$page = trim($page);
			$block = trim($block);
			$message = trim($message);

			if ($page === '' || $block === '' || $message === '') {
				continue;
			}

			$out[] = [
				'page' => $page,
				'block' => $block,
				'message' => $message,
			];
		}

		return $out;
	}

	/**
	 * @return array<string, mixed>
	 */
	private static function load(): array
	{
		if (self::$cached !== null) {
			return self::$cached;
		}

		$file = self::dir() . '/project.php';

		if (!is_readable($file)) {
			self::$cached = [];
			return self::$cached;
		}

		$data = require $file;
		self::$cached = is_array($data) ? $data : [];

		return self::$cached;
	}
}
