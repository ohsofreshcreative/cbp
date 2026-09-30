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

		$content = AcfBlockSerializer::toPostContent($blocks);

		$postarr = [
			'post_type' => 'page',
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
				'Nie udało się utworzyć strony: %s',
				$result->get_error_message()
			));
		}

		$id = (int) $result;

		if ($id <= 0) {
			throw new PageImportException('WordPress nie zwrócił ID nowej strony.');
		}

		return $id;
	}
}
