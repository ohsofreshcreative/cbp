<?php

namespace App\Support;

class PageImporter
{
	public function import(PageImportPayload $payload): int
	{
		$assets = new PageImportAssets($payload->sourceDir);
		$blocks = [];

		foreach ($payload->blocks as $index => $item) {
			$blocks[] = [
				'block' => $item['block'],
				'data' => $assets->hydrate($item['data'], sprintf('blocks[%d].data', $index)),
			];
		}

		if (!function_exists('wp_insert_post')) {
			throw new PageImportException('Importer wymaga WordPress (wp_insert_post).');
		}

		try {
			$content = AcfBlockSerializer::toPostContent($blocks);
		} catch (PageImportException $e) {
			throw $e;
		} catch (\Throwable $e) {
			throw new PageImportException(
				sprintf('Nie udało się zserializować bloków: %s', $e->getMessage()),
				0,
				$e
			);
		}

		$postarr = [
			'post_type' => $payload->postType,
			'post_title' => $payload->title,
			'post_name' => $payload->slug,
			'post_status' => $payload->status,
			'post_content' => $content,
		];

		if (function_exists('wp_slash')) {
			$postarr = wp_slash($postarr);
		}

		$result = wp_insert_post($postarr, true);

		if (is_wp_error($result)) {
			throw new PageImportException(sprintf(
				'Nie udało się utworzyć wpisu: %s',
				$result->get_error_message()
			));
		}

		$id = (int) $result;

		if ($id <= 0) {
			throw new PageImportException('WordPress nie zwrócił ID nowego wpisu.');
		}

		if ($payload->terms !== [] && function_exists('wp_set_object_terms')) {
			foreach ($payload->terms as $taxonomy => $slugs) {
				$set = wp_set_object_terms($id, $slugs, $taxonomy);

				if (is_wp_error($set)) {
					throw new PageImportException(sprintf(
						'Nie udało się przypisać taksonomii %s: %s',
						$taxonomy,
						$set->get_error_message()
					));
				}
			}
		}

		return $id;
	}
}
