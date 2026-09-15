<?php

namespace App\Support;

class PageImporter
{
	public function import(PageImportPayload $payload): int
	{
		if (!function_exists('wp_insert_post')) {
			throw new PageImportException('Importer wymaga WordPress (wp_insert_post).');
		}

		$content = AcfBlockSerializer::toPostContent($payload->blocks);

		$result = wp_insert_post([
			'post_type' => 'page',
			'post_title' => $payload->title,
			'post_name' => $payload->slug,
			'post_status' => $payload->status,
			'post_content' => $content,
		], true);

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
