<?php

/**
 * Importer uruchamiany jawnie przez wp --require=osf-import/bootstrap.php.
 */

if (!defined('WP_CLI') || !WP_CLI) {
	return;
}

foreach ([
	'PageImportException',
	'PageImportPayload',
	'PageImportAssets',
	'AcfBlockSerializer',
	'PageImporter',
	'PageImportCommand',
] as $importClass) {
	require_once __DIR__ . '/src/' . $importClass . '.php';
}

\WP_CLI::add_command('osf page', \App\Cli\PageImportCommand::class);
