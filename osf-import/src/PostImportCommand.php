<?php

namespace App\Cli;

use App\Support\PageImportException;
use App\Support\PostImporter;
use App\Support\PostImportPayload;

class PostImportCommand
{
	/**
	 * Importuje wpis (post) z pliku JSON (treść z Figmy, nie strona Gutenberg).
	 *
	 * ## OPTIONS
	 *
	 * <file>
	 * : Ścieżka do pliku JSON ze wpisem.
	 *
	 * [--porcelain]
	 * : Wypisz tylko ID utworzonego wpisu.
	 *
	 * ## EXAMPLES
	 *
	 *     wp osf post import osf-import/examples/post.json
	 *     wp osf post import resources/imports/posts/czy-wariograf-wykrywa-klamstwo.json
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
			$payload = PostImportPayload::fromFile($this->resolvePath((string) $file));
			$id = (new PostImporter())->import($payload);
		} catch (PageImportException $e) {
			\WP_CLI::error($e->getMessage());
			return;
		} catch (\Throwable $e) {
			\WP_CLI::error(sprintf(
				'Błąd importu wpisu: %s (%s:%d)',
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

		\WP_CLI::success(sprintf('Utworzono wpis o ID %d.', $id));
		\WP_CLI::log(sprintf('ID: %d', $id));
		\WP_CLI::log(sprintf('Tytuł: %s', $payload->title));
		\WP_CLI::log(sprintf('Slug: %s', $payload->slug));
		\WP_CLI::log(sprintf('Status: %s (szkic — w Kokpicie: Wpisy → Wszystkie wpisy, filtr Szkice)', $payload->status));

		if ($payload->author !== null) {
			\WP_CLI::log(sprintf('Autor z JSON: %s (przypisany tylko gdy istnieje użytkownik o tej nazwie wyświetlanej)', $payload->author));
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
