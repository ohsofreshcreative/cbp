<?php

/**
 * Rejestracja komend WP-CLI motywu.
 *
 * Plik może być ładowany z wp-cli.yml jeszcze przed WordPressem,
 * więc autoload motywu trzeba podłączyć tutaj.
 */

namespace App;

if (!defined('WP_CLI') || !WP_CLI) {
	return;
}

$theme_root = dirname(__DIR__);
$autoload = $theme_root . '/vendor/autoload.php';

if (is_readable($autoload)) {
	require_once $autoload;
}

if (!class_exists(\App\Cli\PageImportCommand::class, true)) {
	foreach ([
		'Support/PageImportException.php',
		'Support/PageImportPayload.php',
		'Support/PageImportAssets.php',
		'Support/AcfBlockSerializer.php',
		'Support/PageImporter.php',
		'Cli/PageImportCommand.php',
	] as $relative) {
		$file = __DIR__ . '/' . $relative;

		if (is_readable($file)) {
			require_once $file;
		}
	}
}

if (!class_exists(\App\Cli\PageImportCommand::class, false)) {
	\WP_CLI::error(
		'Nie można załadować App\\Cli\\PageImportCommand. W katalogu motywu uruchom: composer dump-autoload'
	);
}

if (!function_exists(__NAMESPACE__ . '\\register_osf_cli_commands')) {
	function register_osf_cli_commands(): void
	{
		static $registered = false;

		if ($registered) {
			return;
		}

		$registered = true;

		\WP_CLI::add_command('osf', \App\Cli\PageImportCommand::class);
		\WP_CLI::add_command('osf page', \App\Cli\PageImportCommand::class);
	}
}

register_osf_cli_commands();
