<?php

namespace App\Support;

class PostImporter
{
	public function import(PostImportPayload $payload): int
	{
		if (!function_exists('wp_insert_post')) {
			throw new PageImportException('Importer wymaga WordPress (wp_insert_post).');
		}

		$featuredId = null;

		if ($payload->featuredImage !== null) {
			$assets = new PageImportAssets($payload->sourceDir);
			$hydrated = $assets->hydrate([
				'image' => $payload->featuredImage,
			], 'featured_image');

			$featuredId = isset($hydrated['image']) ? (int) $hydrated['image'] : null;

			if ($featuredId !== null && $featuredId <= 0) {
				$featuredId = null;
			}
		}

		$postarr = [
			'post_type' => 'post',
			'post_title' => $payload->title,
			'post_name' => $payload->slug,
			'post_status' => $payload->status,
			'post_content' => $payload->content,
			'post_excerpt' => $payload->excerpt,
		];

		if ($payload->date !== null) {
			$postarr['post_date'] = $payload->date;
			$postarr['post_date_gmt'] = $this->toGmt($payload->date);
		}

		$authorId = $this->findAuthorId($payload->author);

		if ($authorId !== null) {
			$postarr['post_author'] = $authorId;
		}

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

		if ($featuredId !== null && function_exists('set_post_thumbnail')) {
			set_post_thumbnail($id, $featuredId);

			if (function_exists('wp_update_post')) {
				wp_update_post([
					'ID' => $featuredId,
					'post_parent' => $id,
				]);
			}
		}

		$this->assignCategories($id, $payload->categories);

		return $id;
	}

	private function findAuthorId(?string $name): ?int
	{
		if ($name === null || $name === '' || !function_exists('get_users')) {
			return null;
		}

		$users = get_users([
			'number' => 200,
			'fields' => ['ID', 'display_name'],
		]);

		if (!is_array($users)) {
			return null;
		}

		foreach ($users as $user) {
			$display = is_object($user) ? (string) ($user->display_name ?? '') : '';

			if ($display !== '' && strcasecmp($display, $name) === 0) {
				return (int) $user->ID;
			}
		}

		return null;
	}

	/**
	 * @param list<string> $names
	 */
	private function assignCategories(int $postId, array $names): void
	{
		if ($names === [] || !function_exists('wp_set_object_terms')) {
			return;
		}

		$ids = [];

		foreach ($names as $name) {
			$termId = $this->ensureCategory($name);
			if ($termId !== null) {
				$ids[] = $termId;
			}
		}

		if ($ids === []) {
			return;
		}

		$result = wp_set_object_terms($postId, $ids, 'category');

		if (function_exists('is_wp_error') && is_wp_error($result)) {
			throw new PageImportException(sprintf(
				'Nie udało się przypisać kategorii: %s',
				$result->get_error_message()
			));
		}
	}

	private function ensureCategory(string $name): ?int
	{
		if (function_exists('term_exists')) {
			$existing = term_exists($name, 'category');

			if (is_array($existing) && !empty($existing['term_id'])) {
				return (int) $existing['term_id'];
			}

			if (is_int($existing) && $existing > 0) {
				return $existing;
			}
		}

		if (!function_exists('wp_insert_term')) {
			return null;
		}

		$inserted = wp_insert_term($name, 'category');

		if (function_exists('is_wp_error') && is_wp_error($inserted)) {
			throw new PageImportException(sprintf(
				'Nie udało się utworzyć kategorii "%s": %s',
				$name,
				$inserted->get_error_message()
			));
		}

		if (!is_array($inserted) || empty($inserted['term_id'])) {
			throw new PageImportException(sprintf('Nie udało się utworzyć kategorii "%s".', $name));
		}

		return (int) $inserted['term_id'];
	}

	private function toGmt(string $local): string
	{
		if (function_exists('get_gmt_from_date')) {
			$gmt = get_gmt_from_date($local);

			if (is_string($gmt) && $gmt !== '') {
				return $gmt;
			}
		}

		return $local;
	}
}
