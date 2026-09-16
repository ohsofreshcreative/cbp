<?php

/**
 * Rejestracja komend WP-CLI motywu.
 *
 * Ten plik może być wczytany z wp-cli.yml jeszcze przed WordPressem.
 * Nie ładujemy tu autoloadu Sage ani klas importera.
 */

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
			if (!class_exists(\App\Cli\PageImportCommand::class, true)) {
				\WP_CLI::error('Nie można załadować importera. W katalogu motywu uruchom: composer dump-autoload');
			}

			try {
				(new \App\Cli\PageImportCommand())->import(is_array($args) ? $args : [], is_array($assoc_args) ? $assoc_args : []);
			} catch (\Throwable $e) {
				\WP_CLI::error(sprintf('%s (%s:%d)', $e->getMessage(), $e->getFile(), $e->getLine()));
			}
		};

		\WP_CLI::add_command('osf import', $run, ['when' => 'after_wp_load']);
		\WP_CLI::add_command('osf page import', $run, ['when' => 'after_wp_load']);
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
			if (!class_exists(\App\Cli\PostImportCommand::class, true)) {
				\WP_CLI::error('Nie można załadować importera wpisów. W katalogu motywu uruchom: composer dump-autoload');
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
