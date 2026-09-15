<?php

/**
 * Rejestracja komend WP-CLI motywu.
 */

namespace App;

if (!defined('WP_CLI') || !WP_CLI) {
	return;
}

\WP_CLI::add_command('osf page', \App\Cli\PageImportCommand::class);
