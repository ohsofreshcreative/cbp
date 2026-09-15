<?php

/**
 * Rejestracja komend WP-CLI motywu.
 */

namespace App;

if (!defined('WP_CLI') || !WP_CLI) {
	return;
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
