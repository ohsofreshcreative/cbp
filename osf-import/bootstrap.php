<?php

/**
 * Jedyny plik startowy importera Figma → WordPress.
 *
 * W motywie Sage: wp-cli.yml → require: osf-import/bootstrap.php
 * Albo w ThemeServiceProvider przy WP_CLI: require get_theme_file_path('osf-import/bootstrap.php');
 */

if (!defined('OSF_IMPORT_DIR')) {
	define('OSF_IMPORT_DIR', __DIR__);
}

$osfImportSrc = [
	'OsfImportConfig.php',
	'PageImportException.php',
	'PageImportPayload.php',
	'PageImportAssets.php',
	'AcfBlockSerializer.php',
	'PageImporter.php',
	'PostImportPayload.php',
	'PostImporter.php',
	'PageImportCommand.php',
	'PostImportCommand.php',
];

foreach ($osfImportSrc as $osfImportFile) {
	require_once __DIR__ . '/src/' . $osfImportFile;
}

if (!defined('WP_CLI') || !WP_CLI) {
	return;
}

if (!function_exists('osf_register_page_import_command')) {
	function osf_register_page_import_command(): void
	{
		static $registered = false;

		if ($registered) {
			return;
		}

		$registered = true;

		$run = static function ($args, $assoc_args): void {
			if (!class_exists(\App\Cli\PageImportCommand::class, false)) {
				\WP_CLI::error('Nie można załadować importera stron. Sprawdź katalog osf-import/src.');
			}

			try {
				(new \App\Cli\PageImportCommand())->import(is_array($args) ? $args : [], is_array($assoc_args) ? $assoc_args : []);
			} catch (\Throwable $e) {
				\WP_CLI::error(sprintf('%s (%s:%d)', $e->getMessage(), $e->getFile(), $e->getLine()));
			}
		};

		\WP_CLI::add_command('osf import', $run, ['when' => 'after_wp_load']);
		\WP_CLI::add_command('osf page import', $run, ['when' => 'after_wp_load']);
		\WP_CLI::add_command('osf offer import', $run, ['when' => 'after_wp_load']);
	}
}

osf_register_page_import_command();

if (!function_exists('osf_register_post_import_command')) {
	function osf_register_post_import_command(): void
	{
		static $registered = false;

		if ($registered) {
			return;
		}

		$registered = true;

		$run = static function ($args, $assoc_args): void {
			if (!class_exists(\App\Cli\PostImportCommand::class, false)) {
				\WP_CLI::error('Nie można załadować importera wpisów. Sprawdź katalog osf-import/src.');
			}

			try {
				(new \App\Cli\PostImportCommand())->import(is_array($args) ? $args : [], is_array($assoc_args) ? $assoc_args : []);
			} catch (\Throwable $e) {
				\WP_CLI::error(sprintf('%s (%s:%d)', $e->getMessage(), $e->getFile(), $e->getLine()));
			}
		};

		\WP_CLI::add_command('osf post import', $run, ['when' => 'after_wp_load']);
	}
}

osf_register_post_import_command();
