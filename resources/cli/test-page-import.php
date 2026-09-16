<?php

require __DIR__ . '/../../app/Support/PageImportException.php';
require __DIR__ . '/../../app/Support/PageImportPayload.php';
require __DIR__ . '/../../app/Support/PageImportAssets.php';
require __DIR__ . '/../../app/Support/AcfBlockSerializer.php';
require __DIR__ . '/../../app/Support/PageImporter.php';

use App\Support\AcfBlockSerializer;
use App\Support\PageImporter;
use App\Support\PageImportAssets;
use App\Support\PageImportException;
use App\Support\PageImportPayload;

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

$example = __DIR__ . '/hero-page.example.json';
$payload = PageImportPayload::fromFile($example);

expect_true($payload->title === 'Hero import test', 'title z przykładu');
expect_true($payload->slug === 'hero-import-test', 'slug z przykładu');
expect_true($payload->status === 'draft', 'domyślny/przyjęty status draft');
expect_true(count($payload->blocks) === 1, 'jeden blok');
expect_true($payload->blocks[0]['block'] === 'hero', 'slug hero');
expect_true(($payload->blocks[0]['data']['g_hero']['button1']['title'] ?? '') === 'Kontakt', 'zagnieżdżone data.g_hero.button1');
expect_true(($payload->blocks[0]['data']['g_hero']['image']['src'] ?? '') === 'resources/imports/assets/hero-test.jpg', 'asset src w JSON');
expect_true(is_string($payload->sourceDir) && str_ends_with($payload->sourceDir, 'cli'), 'sourceDir z pliku JSON');

$withoutStatus = PageImportPayload::fromJson(json_encode([
	'title' => 'Bez statusu',
	'blocks' => [['block' => 'hero', 'data' => ['background' => 'none']]],
], JSON_THROW_ON_ERROR));
expect_true($withoutStatus->status === 'draft', 'brak statusu → draft');
expect_true($withoutStatus->slug === 'bez-statusu', 'slug z title');
expect_true($withoutStatus->postType === 'page', 'domyślny post_type page');
expect_true($withoutStatus->terms === [], 'brak terms → pusta mapa');

$offerPayload = PageImportPayload::fromJson(json_encode([
	'title' => 'Ekspertyza test',
	'slug' => 'ekspertyza-test',
	'post_type' => 'offer',
	'terms' => ['offer_category' => ['Instytucje Sądowe i Organy Ścigania']],
	'blocks' => [['block' => 'hero', 'data' => ['background' => 'none']]],
], JSON_THROW_ON_ERROR));
expect_true($offerPayload->postType === 'offer', 'post_type offer');
expect_true(($offerPayload->terms['offer_category'][0] ?? '') === 'Instytucje Sądowe i Organy Ścigania', 'terms.offer_category');

expect_exception(
	fn () => PageImportPayload::fromArray(['title' => 'X', 'post_type' => 'product', 'blocks' => []]),
	'Nieobsługiwany post_type',
	'zły post_type'
);
expect_exception(
	fn () => PageImportPayload::fromArray(['title' => 'X', 'terms' => ['a', 'b'], 'blocks' => []]),
	'terms',
	'terms jako lista'
);

$content = AcfBlockSerializer::toPostContent($payload->blocks);
expect_true(str_contains($content, '<!-- wp:acf/hero '), 'komentarz Gutenberga acf/hero');
expect_true(str_ends_with(trim($content), '/-->'), 'self-closing block');
expect_true(str_contains($content, '"name":"acf/hero"'), 'atrybut name');
expect_true(str_contains($content, '"mode":"edit"'), 'mode edit');
expect_true(str_contains($content, 'Centrum Bada\u0144 Poligraficznych') || str_contains($content, 'Centrum Badań Poligraficznych'), 'treść header w data');
expect_true(str_contains($content, '\\u003cp\\u003e') || str_contains($content, '<p>'), 'WYSIWYG zachowany');

$decodedAttrs = [];
if (preg_match('/<!-- wp:acf\/hero\s+(\{.*\})\s+\/-->/s', $content, $m)) {
	$json = str_replace(['\\u003c', '\\u003e', '\\u0026', '\\u0022', '\\u002d\\u002d'], ['<', '>', '&', '"', '--'], $m[1]);
	$decodedAttrs = json_decode($json, true) ?: [];
}
expect_true(($decodedAttrs['data']['g_hero']['button1']['url'] ?? '') === '/kontakt', 'data ACF w atrybutach bloku');
expect_true(($decodedAttrs['data']['background'] ?? '') === 'none', 'proste pole background');
expect_true(str_starts_with((string) ($decodedAttrs['id'] ?? ''), 'block_'), 'id bloku block_*');

expect_exception(fn () => PageImportPayload::fromJson('{'), 'Niepoprawny JSON', 'zepsuty JSON');
expect_exception(fn () => PageImportPayload::fromArray(['blocks' => []]), 'title', 'brak title');
expect_exception(fn () => PageImportPayload::fromArray(['title' => 'X', 'status' => 'live', 'blocks' => []]), 'Nieobsługiwany status', 'zły status');
expect_exception(fn () => PageImportPayload::fromArray(['title' => 'X', 'blocks' => [['block' => 'doesnotexist', 'data' => []]]]), 'nie istnieje', 'nieznany blok');
expect_true(is_readable(PageImportPayload::blockClassPath('hero')), 'Hero.php jest w app/Blocks na cursor-work');
expect_true(is_readable(PageImportPayload::blockClassPath('banner')), 'Banner.php jest w app/Blocks');
expect_true(is_readable(PageImportPayload::blockClassPath('action')), 'Action.php jest w app/Blocks');
expect_exception(fn () => PageImportPayload::fromArray(['title' => 'X', 'blocks' => [['block' => 'hero-banner', 'data' => []]]]), 'niepoprawny', 'slug z myślnikiem');
expect_exception(fn () => PageImportPayload::fromArray(['title' => 'X', 'blocks' => [['block' => 'hero', 'data' => ['a', 'b']]]]), 'obiektem', 'data jako lista');
expect_exception(fn () => PageImportPayload::fromFile('/tmp/missing-osf-page.json'), 'Nie można odczytać', 'brak pliku');
expect_exception(fn () => (new PageImporter())->import($withoutStatus), 'wp_insert_post', 'importer bez WordPressa');

expect_true(PageImportAssets::isAsset(['src' => 'a.jpg', 'alt' => 'x']), 'rozpoznaje {src, alt}');
expect_true(!PageImportAssets::isAsset(['title' => 'Kontakt', 'url' => '/x', 'target' => '']), 'link to nie asset');
expect_true(!PageImportAssets::isAsset(['a', 'b']), 'lista to nie asset');
expect_true(!PageImportAssets::isAsset(12), 'skalar to nie asset');

expect_exception(
	fn () => (new PageImportAssets())->hydrate(['image' => ['src' => 'no-such-file.jpg', 'alt' => 'x']], 'data'),
	'nie znaleziono pliku',
	'brak pliku obrazu'
);

expect_exception(
	fn () => (new PageImporter())->import($payload),
	'Import obrazów wymaga WordPress',
	'istniejący obraz bez WP — bez tworzenia strony'
);

expect_exception(
	fn () => (new PageImportAssets())->hydrate(['image' => ['src' => '', 'alt' => 'x']], 'data'),
	'src',
	'puste src'
);

expect_exception(
	fn () => (new PageImportAssets())->hydrate(['image' => ['src' => 'https://example.com/a.jpg', 'alt' => 'x']], 'data'),
	'URL',
	'src jako URL'
);

$notImage = sys_get_temp_dir() . '/osf-not-image.txt';
file_put_contents($notImage, 'not-an-image');
expect_exception(
	fn () => (new PageImportAssets())->hydrate(['image' => ['src' => $notImage, 'alt' => 'x']], 'data'),
	'obrazem',
	'plik nie jest obrazem'
);
@unlink($notImage);

try {
	(new PageImportAssets())->hydrate([
		'g_hero' => [
			'image' => ['src' => 'missing-hero.jpg', 'alt' => 'a'],
			'badges' => [
				['icon' => ['src' => 'missing-icon.png', 'alt' => 'b']],
			],
		],
		'r_hero' => [
			['image' => ['src' => 'missing-tile.jpg', 'alt' => 'c']],
		],
	], 'data');
	expect_true(false, 'zagnieżdżone braki (brak wyjątku)');
} catch (PageImportException $e) {
	$msg = $e->getMessage();
	expect_true(str_contains($msg, 'g_hero.image'), 'błąd wskazuje g_hero.image');
	expect_true(str_contains($msg, 'g_hero.badges[0].icon'), 'błąd wskazuje g_hero.badges[0].icon');
	expect_true(str_contains($msg, 'r_hero[0].image'), 'błąd wskazuje r_hero[0].image');
}

expect_true((new PageImportAssets())->resolveFile('resources/imports/assets/hero-test.jpg') !== null, 'resolve przykładowego JPG');

$encoded = AcfBlockSerializer::prepareData('hero', [
	'g_hero' => ['header' => 'A'],
	'_g_hero' => 'field_should_strip',
]);
expect_true(!array_key_exists('_g_hero', $encoded), 'bez ACF strip kluczy _*');
expect_true(($encoded['g_hero']['header'] ?? '') === 'A', 'proste data bez ACF');

if (!function_exists('acf_get_field_groups')) {
	function acf_get_field_groups(array $filter = []): array
	{
		$block = $filter['block'] ?? '';

		if ($block === 'acf/hero' || $block === '') {
			return [['key' => 'group_hero']];
		}

		return [];
	}
}

if (!function_exists('acf_get_fields')) {
	function acf_get_fields($group): array
	{
		return [
			['name' => 'Elementy', 'type' => 'tab', 'key' => 'field_tab'],
			[
				'name' => 'g_hero',
				'type' => 'group',
				'key' => 'field_hero_g_hero',
				'sub_fields' => [
					['name' => 'header', 'type' => 'wysiwyg', 'key' => 'field_hero_header'],
					['name' => 'text', 'type' => 'wysiwyg', 'key' => 'field_hero_text'],
					['name' => 'image', 'type' => 'image', 'key' => 'field_hero_image'],
					['name' => 'button1', 'type' => 'link', 'key' => 'field_hero_button1'],
					[
						'name' => 'badges',
						'type' => 'repeater',
						'key' => 'field_hero_badges',
						'sub_fields' => [
							['name' => 'icon', 'type' => 'image', 'key' => 'field_hero_badge_icon'],
							['name' => 'text', 'type' => 'text', 'key' => 'field_hero_badge_text'],
						],
					],
				],
			],
			[
				'name' => 'r_hero',
				'type' => 'repeater',
				'key' => 'field_hero_r_hero',
				'sub_fields' => [
					['name' => 'image', 'type' => 'image', 'key' => 'field_hero_r_image'],
					['name' => 'title', 'type' => 'text', 'key' => 'field_hero_r_title'],
				],
			],
			['name' => 'background', 'type' => 'select', 'key' => 'field_hero_background'],
			['name' => 'nomt', 'type' => 'true_false', 'key' => 'field_hero_nomt'],
		];
	}
}

$withKeys = AcfBlockSerializer::prepareData('hero', [
	'g_hero' => [
		'header' => '<p>Hi</p>',
		'button1' => ['url' => '/x', 'title' => 'X', 'target' => ''],
	],
	'r_hero' => [
		['title' => 'Kafelek'],
	],
	'background' => 'none',
	'nomt' => false,
]);
expect_true(($withKeys['_g_hero'] ?? '') === 'field_hero_g_hero', 'klucz grupy g_hero');
expect_true(($withKeys['g_hero_header'] ?? '') === '<p>Hi</p>', 'spłaszczone g_hero_header');
expect_true(($withKeys['_g_hero_header'] ?? '') === 'field_hero_header', 'klucz podpola header');
expect_true(($withKeys['field_hero_header'] ?? '') === '<p>Hi</p>', 'v3 field_hero_header');
expect_true(($withKeys['g_hero_button1']['url'] ?? '') === '/x', 'spłaszczony link button1');
expect_true(($withKeys['_g_hero_button1'] ?? '') === 'field_hero_button1', 'klucz linku button1');
expect_true(($withKeys['_background'] ?? '') === 'field_hero_background', 'klucz select background');
expect_true(($withKeys['nomt'] ?? null) === 0, 'true_false nomt jako 0');
expect_true(($withKeys['r_hero'] ?? null) === 1, 'repeater zapisany jako liczba wierszy');
expect_true(($withKeys['r_hero_0_title'] ?? '') === 'Kafelek', 'wiersz repeatera r_hero_0_title');
expect_true(($withKeys['_r_hero_0_title'] ?? '') === 'field_hero_r_title', 'klucz subpola repeatera');
expect_true(!isset($withKeys['Elementy']), 'pomija tab');
expect_true(($withKeys['g_hero'] ?? null) === '', 'placeholder grupy g_hero');

$exampleWithAcf = AcfBlockSerializer::prepareData('hero', $payload->blocks[0]['data']);
expect_true(($exampleWithAcf['g_hero_header'] ?? '') === '<p>Centrum Badań Poligraficznych</p>', 'przykład JSON: header w g_hero_header');
expect_true(($exampleWithAcf['g_hero_text'] ?? '') === '<p>Prosty import bloku Hero.</p>', 'przykład JSON: text');
expect_true(($exampleWithAcf['g_hero_button1']['title'] ?? '') === 'Kontakt', 'przykład JSON: button1');
expect_true(($exampleWithAcf['background'] ?? '') === 'none', 'przykład JSON: background');
expect_true(($exampleWithAcf['nomt'] ?? null) === 0, 'przykład JSON: nomt');

expect_exception(
	fn () => AcfBlockSerializer::prepareData('missingblock', ['header' => 'X']),
	'Nie znaleziono grupy pól ACF',
	'brak grupy pól przy aktywnym ACF'
);

$GLOBALS['osf_upload_count'] = 0;
$GLOBALS['osf_alts'] = [];
$GLOBALS['osf_next_attachment_id'] = 101;
$GLOBALS['osf_inserted_content'] = '';

if (!function_exists('wp_upload_bits')) {
	function wp_upload_bits($name, $deprecated, $bits)
	{
		$GLOBALS['osf_upload_count']++;
		$dest = sys_get_temp_dir() . '/osf-upload-' . $name;
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
		return ['file' => $file, 'width' => 640, 'height' => 360];
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

if (!function_exists('is_wp_error')) {
	function is_wp_error($thing)
	{
		return false;
	}
}

if (!function_exists('wp_insert_post')) {
	function wp_insert_post($postarr, $wp_error = false)
	{
		$GLOBALS['osf_inserted'] = $postarr;
		$GLOBALS['osf_inserted_content'] = $postarr['post_content'] ?? '';
		return 55;
	}
}

if (!function_exists('wp_set_object_terms')) {
	function wp_set_object_terms($object_id, $terms, $taxonomy, $append = false)
	{
		$GLOBALS['osf_terms'] = [
			'id' => (int) $object_id,
			'taxonomy' => $taxonomy,
			'terms' => $terms,
		];
		return [1];
	}
}

$hydrated = (new PageImportAssets())->hydrate([
	'g_hero' => [
		'header' => 'H',
		'image' => [
			'src' => 'resources/imports/assets/hero-test.jpg',
			'alt' => 'Alt hero',
		],
		'badges' => [
			[
				'icon' => [
					'src' => 'resources/imports/assets/hero-test.jpg',
					'alt' => 'Alt badge',
				],
				'text' => 'T',
			],
		],
	],
	'r_hero' => [
		[
			'image' => [
				'src' => 'resources/imports/assets/hero-test.jpg',
				'alt' => 'Alt tile',
			],
			'title' => 'Kafelek',
		],
	],
], 'data');

expect_true(($hydrated['g_hero']['image'] ?? null) === 101, 'g_hero.image → ID załącznika');
expect_true(($hydrated['g_hero']['badges'][0]['icon'] ?? null) === 101, 'g_hero.badges[0].icon reuse ID');
expect_true(($hydrated['r_hero'][0]['image'] ?? null) === 101, 'r_hero[0].image reuse ID');
expect_true(($hydrated['g_hero']['header'] ?? '') === 'H', 'pozostałe pola nietknięte');
expect_true($GLOBALS['osf_upload_count'] === 1, 'ten sam plik importowany raz');
expect_true(($GLOBALS['osf_alts'][101] ?? '') === 'Alt hero', 'alt text na załączniku');

$encodedAssets = AcfBlockSerializer::prepareData('hero', $hydrated);
expect_true(($encodedAssets['g_hero_image'] ?? null) === 101, 'ACF image zapisane jako ID');
expect_true(($encodedAssets['field_hero_image'] ?? null) === 101, 'v3 field_hero_image = ID');
expect_true(($encodedAssets['g_hero_badges_0_icon'] ?? null) === 101, 'zagnieżdżony repeater icon jako ID');
expect_true(($encodedAssets['r_hero_0_image'] ?? null) === 101, 'r_hero[].image jako ID');

$pageId = (new PageImporter())->import($payload);
expect_true($pageId === 55, 'import strony z obrazem zwraca ID');
expect_true(($GLOBALS['osf_inserted']['post_type'] ?? '') === 'page', 'import strony ustawia post_type page');
expect_true(
	(bool) preg_match('/"g_hero_image":\s*\d+/', (string) $GLOBALS['osf_inserted_content']),
	'post_content zawiera ID obrazu, nie ścieżkę src'
);
expect_true(!str_contains((string) $GLOBALS['osf_inserted_content'], 'hero-test.jpg'), 'ścieżka src nie zostaje w bloku');

$offerFile = dirname(__DIR__, 2) . '/resources/imports/offers/ekspertyza-poligraficzna-na-potrzeby-postepowania.json';
$offerFromFile = PageImportPayload::fromFile($offerFile);
expect_true($offerFromFile->postType === 'offer', 'JSON oferty: post_type offer');
expect_true($offerFromFile->status === 'draft', 'JSON oferty: status draft');
expect_true($offerFromFile->slug === 'ekspertyza-poligraficzna-na-potrzeby-postepowania', 'JSON oferty: slug z ramki');
expect_true(($offerFromFile->terms['offer_category'][0] ?? '') === 'Instytucje Sądowe i Organy Ścigania', 'JSON oferty: kategoria CPT');
expect_true($offerFromFile->blocks[0]['block'] === 'banner', 'JSON oferty: pierwszy blok banner');
expect_true(!in_array('action', array_column($offerFromFile->blocks, 'block'), true), 'JSON oferty: bez bloku Action');

$b2cFile = dirname(__DIR__, 2) . '/resources/imports/b2c.json';
$b2cFromFile = PageImportPayload::fromFile($b2cFile);
$b2cBlocks = array_column($b2cFromFile->blocks, 'block');
expect_true($b2cFromFile->slug === 'b2c', 'JSON B2C: slug');
expect_true($b2cBlocks === ['banner', 'problem', 'wehelp', 'offers', 'reach', 'proces', 'values', 'faq', 'cta'], 'JSON B2C: kolejność bloków z ramki Devs');
expect_true(!in_array('solutions', $b2cBlocks, true), 'JSON B2C: Wehelp nie jest mapowany na solutions');
expect_true(is_readable(PageImportPayload::blockClassPath('wehelp')), 'Wehelp.php jest w app/Blocks');
expect_true(($b2cFromFile->blocks[2]['data']['g_wehelp']['image']['src'] ?? '') === 'resources/imports/assets/b2c-wehelp.jpg', 'JSON B2C: unikalne JPG Wehelp');

$b2bFile = dirname(__DIR__, 2) . '/resources/imports/b2b.json';
$b2bFromFile = PageImportPayload::fromFile($b2bFile);
$b2bBlocks = array_column($b2bFromFile->blocks, 'block');
expect_true(in_array('solutions', $b2bBlocks, true), 'JSON B2B: Solutions zostaje solutions');
expect_true(!in_array('wehelp', $b2bBlocks, true), 'JSON B2B: bez Wehelp');

$faqBlade = file_get_contents(dirname(__DIR__, 2) . '/resources/views/blocks/faq.blade.php');
expect_true(is_string($faqBlade) && str_contains($faqBlade, '<details'), 'FAQ: natywny details');
expect_true(is_string($faqBlade) && str_contains($faqBlade, '<summary'), 'FAQ: natywny summary');
expect_true(is_string($faqBlade) && !str_contains($faqBlade, 'tab-check'), 'FAQ: bez ukrytego checkboxa');
expect_true(is_string($faqBlade) && str_contains($faqBlade, '__content'), 'FAQ: panel odpowiedzi zawsze w markupu');

$GLOBALS['osf_inserted'] = [];
$GLOBALS['osf_terms'] = [];
$offerId = (new PageImporter())->import($offerPayload);
expect_true($offerId === 55, 'import CPT oferta zwraca ID');
expect_true(($GLOBALS['osf_inserted']['post_type'] ?? '') === 'offer', 'import CPT ustawia post_type offer');
expect_true(($GLOBALS['osf_inserted']['post_name'] ?? '') === 'ekspertyza-test', 'import CPT zachowuje slug');
expect_true(($GLOBALS['osf_terms']['taxonomy'] ?? '') === 'offer_category', 'import CPT przypisuje taksonomię');
expect_true(($GLOBALS['osf_terms']['terms'][0] ?? '') === 'Instytucje Sądowe i Organy Ścigania', 'import CPT zachowuje nazwę kategorii');

echo "\n{$passed} passed, {$failed} failed\n";
exit($failed === 0 ? 0 : 1);
