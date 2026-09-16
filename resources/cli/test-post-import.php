<?php

require __DIR__ . '/../../app/Support/PageImportException.php';
require __DIR__ . '/../../app/Support/PageImportAssets.php';
require __DIR__ . '/../../app/Support/AcfBlockSerializer.php';
require __DIR__ . '/../../app/Support/PostImportPayload.php';
require __DIR__ . '/../../app/Support/PostImporter.php';

use App\Support\PageImportException;
use App\Support\PostImporter;
use App\Support\PostImportPayload;

$failed = 0;
$passed = 0;

function expect_true(bool $ok, string $msg): void
{
	global $failed, $passed;
	if ($ok) {
		$passed++;
		echo "OK  {$msg}\n";
		return;
	}
	$failed++;
	echo "FAIL  {$msg}\n";
}

function expect_exception(callable $fn, string $needle, string $msg): void
{
	try {
		$fn();
		expect_true(false, $msg . ' (brak wyjątku)');
	} catch (PageImportException $e) {
		expect_true(str_contains($e->getMessage(), $needle), $msg . ' → ' . $e->getMessage());
	}
}

$example = __DIR__ . '/post.example.json';
$payload = PostImportPayload::fromFile($example);

expect_true($payload->title === 'Wpis testowy importu', 'title z przykładu wpisu');
expect_true($payload->slug === 'wpis-testowy-importu', 'slug z przykładu wpisu');
expect_true($payload->status === 'draft', 'status draft');
expect_true($payload->date === '2026-06-12 09:00:00', 'data Y-m-d → 09:00:00');
expect_true($payload->author === 'Katarzyna Jóźwiak', 'autor z JSON');
expect_true($payload->categories === ['Wiedza i metodologia'], 'kategoria z JSON');
expect_true(str_contains($payload->content, '<h2>Nagłówek testowy</h2>'), 'content Gutenberg z JSON');
expect_true($payload->featuredImage === null, 'przykład bez miniaturki');

$fromFile = PostImportPayload::fromFile(
	dirname(__DIR__) . '/imports/posts/czy-wariograf-wykrywa-klamstwo.json'
);
expect_true($fromFile->slug === 'czy-wariograf-wykrywa-klamstwo', 'slug wpisu z Figmy');
expect_true(str_contains($fromFile->content, 'Czy wariograf naprawdę wykrywa kłamstwo?'), 'H2 z pliku HTML');
expect_true(str_contains($fromFile->content, 'Czy stres może wpłynąć na wynik?'), 'ostatnia sekcja z Figmy');
expect_true(str_contains($fromFile->content, '<!-- osf:embed:action -->'), 'znacznik embed action w HTML');
expect_true(count($fromFile->embeds) === 1, 'jeden embed ACF');
expect_true(($fromFile->embeds[0]['block'] ?? '') === 'action', 'embed to blok action');
expect_true(str_contains((string) ($fromFile->embeds[0]['data']['g_action']['header'] ?? ''), 'Masz więcej pytań'), 'copy CTA z Figmy');
expect_true(!str_contains($fromFile->content, 'Jak ekspert interpretuje wyniki?'), 'bez wymyślonych sekcji ze spisu');
expect_true(($fromFile->featuredImage['src'] ?? '') === 'resources/imports/assets/post-wariograf.jpg', 'miniaturka JPG');
expect_true($fromFile->excerpt !== '', 'excerpt z leadu');

expect_exception(fn () => PostImportPayload::fromJson('{'), 'Niepoprawny JSON', 'zepsuty JSON wpisu');
expect_exception(fn () => PostImportPayload::fromArray(['content' => '<p>x</p>']), 'title', 'brak title');
expect_exception(fn () => PostImportPayload::fromArray(['title' => 'X']), 'content', 'brak content');
expect_exception(fn () => PostImportPayload::fromArray(['title' => 'X', 'status' => 'live', 'content' => '<p>x</p>']), 'Nieobsługiwany status', 'zły status wpisu');
expect_exception(fn () => PostImportPayload::fromArray(['title' => 'X', 'date' => '12 czerwca 2026', 'content' => '<p>x</p>']), 'Y-m-d', 'zły format daty');
expect_exception(
	fn () => PostImportPayload::fromArray(['title' => 'X', 'content' => '<p>x</p>', 'featured_image' => ['alt' => 'a']]),
	'featured_image.src',
	'miniaturka bez src'
);

$withoutWp = PostImportPayload::fromArray([
	'title' => 'Bez WP',
	'content' => '<p>Treść</p>',
]);
expect_exception(fn () => (new PostImporter())->import($withoutWp), 'wp_insert_post', 'importer wpisu bez WordPressa');

$GLOBALS['osf_postarr'] = [];
$GLOBALS['osf_thumbnail'] = null;
$GLOBALS['osf_terms'] = [];
$GLOBALS['osf_inserted_terms'] = [];
$GLOBALS['osf_upload_count'] = 0;
$GLOBALS['osf_next_attachment_id'] = 201;
$GLOBALS['osf_alts'] = [];

if (!function_exists('wp_insert_post')) {
	function wp_insert_post($postarr, $wp_error = false)
	{
		$GLOBALS['osf_postarr'] = $postarr;
		return 77;
	}
}

if (!function_exists('wp_slash')) {
	function wp_slash($value)
	{
		return $value;
	}
}

if (!function_exists('is_wp_error')) {
	function is_wp_error($thing)
	{
		return false;
	}
}

if (!function_exists('set_post_thumbnail')) {
	function set_post_thumbnail($post_id, $thumbnail_id)
	{
		$GLOBALS['osf_thumbnail'] = [(int) $post_id, (int) $thumbnail_id];
		return true;
	}
}

if (!function_exists('wp_update_post')) {
	function wp_update_post($postarr)
	{
		return $postarr['ID'] ?? 0;
	}
}

if (!function_exists('get_users')) {
	function get_users($args = [])
	{
		return [
			(object) ['ID' => 3, 'display_name' => 'Katarzyna Jóźwiak'],
		];
	}
}

if (!function_exists('term_exists')) {
	function term_exists($term, $taxonomy = '', $parent = null)
	{
		return null;
	}
}

if (!function_exists('wp_insert_term')) {
	function wp_insert_term($term, $taxonomy, $args = [])
	{
		$id = 15;
		$GLOBALS['osf_inserted_terms'][] = ['name' => $term, 'taxonomy' => $taxonomy];
		return ['term_id' => $id, 'term_taxonomy_id' => $id];
	}
}

if (!function_exists('wp_set_object_terms')) {
	function wp_set_object_terms($object_id, $terms, $taxonomy, $append = false)
	{
		$GLOBALS['osf_terms'] = [
			'post_id' => (int) $object_id,
			'terms' => $terms,
			'taxonomy' => $taxonomy,
		];
		return $terms;
	}
}

if (!function_exists('get_gmt_from_date')) {
	function get_gmt_from_date($string, $format = 'Y-m-d H:i:s')
	{
		return $string;
	}
}

if (!function_exists('wp_upload_bits')) {
	function wp_upload_bits($name, $deprecated, $bits)
	{
		$GLOBALS['osf_upload_count']++;
		$dest = sys_get_temp_dir() . '/osf-post-upload-' . $name;
		file_put_contents($dest, $bits);
		return ['file' => $dest, 'url' => 'http://example.test/' . $name, 'type' => 'image/jpeg', 'error' => false];
	}
}

if (!function_exists('wp_insert_attachment')) {
	function wp_insert_attachment($args, $file = false, $parent = 0, $wp_error = false)
	{
		$id = (int) $GLOBALS['osf_next_attachment_id'];
		$GLOBALS['osf_next_attachment_id']++;
		return $id;
	}
}

if (!function_exists('wp_generate_attachment_metadata')) {
	function wp_generate_attachment_metadata($id, $file)
	{
		return ['file' => $file, 'width' => 1280, 'height' => 414];
	}
}

if (!function_exists('wp_update_attachment_metadata')) {
	function wp_update_attachment_metadata($id, $data)
	{
		return true;
	}
}

if (!function_exists('update_post_meta')) {
	function update_post_meta($id, $key, $value)
	{
		if ($key === '_wp_attachment_image_alt') {
			$GLOBALS['osf_alts'][(int) $id] = $value;
		}
		return true;
	}
}

if (!function_exists('wp_check_filetype')) {
	function wp_check_filetype($filename, $mimes = null)
	{
		return ['ext' => 'jpg', 'type' => 'image/jpeg'];
	}
}

if (!function_exists('sanitize_file_name')) {
	function sanitize_file_name($filename)
	{
		return $filename;
	}
}

if (!function_exists('sanitize_text_field')) {
	function sanitize_text_field($str)
	{
		return is_string($str) ? trim($str) : $str;
	}
}

if (!function_exists('wp_delete_attachment')) {
	function wp_delete_attachment($id, $force = false)
	{
		return true;
	}
}

$id = (new PostImporter())->import($fromFile);
expect_true($id === 77, 'import wpisu zwraca ID');
expect_true(($GLOBALS['osf_postarr']['post_type'] ?? '') === 'post', 'post_type = post');
expect_true(($GLOBALS['osf_postarr']['post_name'] ?? '') === 'czy-wariograf-wykrywa-klamstwo', 'slug zapisany');
expect_true(($GLOBALS['osf_postarr']['post_status'] ?? '') === 'draft', 'status draft');
expect_true(($GLOBALS['osf_postarr']['post_author'] ?? null) === 3, 'autor dopasowany po display_name');
expect_true(($GLOBALS['osf_postarr']['post_date'] ?? '') === '2026-06-12 09:00:00', 'data publikacji z Figmy');
expect_true(str_contains((string) ($GLOBALS['osf_postarr']['post_content'] ?? ''), 'Co mierzy poligraf?'), 'treść z Figmy w post_content');
expect_true(str_contains((string) ($GLOBALS['osf_postarr']['post_content'] ?? ''), '<!-- wp:acf/action'), 'blok acf/action w treści wpisu');
expect_true(str_contains((string) ($GLOBALS['osf_postarr']['post_content'] ?? ''), 'Masz więcej pytań'), 'nagłówek CTA - Wpis w treści');
expect_true(!str_contains((string) ($GLOBALS['osf_postarr']['post_content'] ?? ''), 'osf:embed:action'), 'znacznik embed zastąpiony');
expect_true(($GLOBALS['osf_thumbnail'][0] ?? null) === 77, 'miniaturka przypięta do wpisu');
expect_true(($GLOBALS['osf_thumbnail'][1] ?? null) === 201, 'ID miniaturki z importu JPG');
expect_true(($GLOBALS['osf_terms']['taxonomy'] ?? '') === 'category', 'przypisano kategorię');
expect_true(($GLOBALS['osf_inserted_terms'][0]['name'] ?? '') === 'Wiedza i metodologia', 'kategoria z Figmy');
expect_true($GLOBALS['osf_upload_count'] === 2, 'miniaturka i zdjęcie Action wgrane');

expect_exception(
	fn () => (new PostImporter())->import(PostImportPayload::fromArray([
		'title' => 'X',
		'content' => '<p>bez znacznika</p>',
		'embeds' => [['id' => 'action', 'block' => 'action', 'data' => []]],
	])),
	'brakuje znacznika',
	'embed action bez znacznika w HTML'
);

$unknownAuthor = PostImportPayload::fromArray([
	'title' => 'Bez autora',
	'author' => 'Nieistniejąca Osoba',
	'content' => '<p>x</p>',
]);
$GLOBALS['osf_postarr'] = [];
(new PostImporter())->import($unknownAuthor);
expect_true(!array_key_exists('post_author', $GLOBALS['osf_postarr']), 'brak autora, gdy nie ma usera');

echo "\n{$passed} passed, {$failed} failed\n";
exit($failed === 0 ? 0 : 1);
