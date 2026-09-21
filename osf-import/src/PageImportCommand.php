<?php

namespace App\Cli;

use App\Support\PageImportException;
use App\Support\PageImporter;
use App\Support\PageImportPayload;

class PageImportCommand
{
	/**
	 * Importuje stronę Gutenberg z pliku JSON (bloki ACF).
	 *
	 * ## OPTIONS
	 *
	 * <file>
	 * : Ścieżka do pliku JSON ze stroną.
	 *
	 * [--porcelain]
	 * : Wypisz tylko ID utworzonej strony.
	 *
	 * ## EXAMPLES
	 *
	 *     wp osf page import osf-import/examples/page.json
	 *     wp osf offer import resources/imports/offers/ekspertyza-poligraficzna-na-potrzeby-postepowania.json
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string> $args
	 * @param array<string, mixed> $assoc_args
	 */
	public function import($args, $assoc_args): void
	{
		$file = $args[0] ?? '';

		try {
			$payload = PageImportPayload::fromFile($this->resolvePath((string) $file));
			$importer = new PageImporter();
			$id = $importer->import($payload);
		} catch (PageImportException $e) {
			\WP_CLI::error($e->getMessage());
			return;
		} catch (\Throwable $e) {
			\WP_CLI::error(sprintf(
				'Błąd importu: %s (%s:%d)',
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			));
			return;
		}

		$porcelain = !empty($assoc_args['porcelain']);

		if ($porcelain) {
			\WP_CLI::line((string) $id);
			return;
		}

		$kind = $payload->postType === 'offer' ? 'wpis CPT oferta' : 'stronę';
		$action = $importer->updated ? 'Zaktualizowano' : 'Utworzono';

		\WP_CLI::success(sprintf(
			'%s %s o ID %d.',
			$action,
			$kind,
			$id
		));
		\WP_CLI::log(sprintf('ID: %d', $id));
		\WP_CLI::log(sprintf('Tytuł: %s', $payload->title));
		\WP_CLI::log(sprintf('Slug: %s', $payload->slug));
		\WP_CLI::log(sprintf('Typ: %s', $payload->postType));
		\WP_CLI::log(sprintf('Bloki: %s', implode(', ', array_column($payload->blocks, 'block'))));

		if ($payload->postType === 'offer') {
			\WP_CLI::log(sprintf('Status: %s (szkic — w Kokpicie: Oferta → Wszystkie oferty, filtr Szkice)', $payload->status));
		} else {
			\WP_CLI::log(sprintf('Status: %s (szkic — w Kokpicie: Strony → Wszystkie strony, filtr Szkice)', $payload->status));
		}

		if (function_exists('get_edit_post_link')) {
			$edit = get_edit_post_link($id, 'raw');

			if (is_string($edit) && $edit !== '') {
				\WP_CLI::log(sprintf('Edycja: %s', $edit));
			}
		}
	}

	private function resolvePath(string $file): string
	{
		if ($file === '') {
			return '';
		}

		if ($this->isAbsolutePath($file)) {
			return $file;
		}

		$fromCwd = getcwd() !== false ? getcwd() . DIRECTORY_SEPARATOR . $file : $file;

		if (is_readable($fromCwd)) {
			return $fromCwd;
		}

		if (function_exists('get_theme_file_path')) {
			$fromTheme = get_theme_file_path($file);

			if (is_readable($fromTheme)) {
				return $fromTheme;
			}
		}

		return $fromCwd;
	}

	private function isAbsolutePath(string $path): bool
	{
		return str_starts_with($path, '/') || (strlen($path) > 2 && ctype_alpha($path[0]) && $path[1] === ':');
	}
}
