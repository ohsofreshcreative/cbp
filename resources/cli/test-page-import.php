<?php

require __DIR__ . '/../../app/Support/PageImportException.php';
require __DIR__ . '/../../app/Support/PageImportPayload.php';
require __DIR__ . '/../../app/Support/AcfBlockSerializer.php';
require __DIR__ . '/../../app/Support/PageImporter.php';

use App\Support\AcfBlockSerializer;
use App\Support\PageImporter;
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

$withoutStatus = PageImportPayload::fromJson(json_encode([
	'title' => 'Bez statusu',
	'blocks' => [['block' => 'hero', 'data' => ['background' => 'none']]],
], JSON_THROW_ON_ERROR));
expect_true($withoutStatus->status === 'draft', 'brak statusu → draft');
expect_true($withoutStatus->slug === 'bez-statusu', 'slug z title');

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
expect_exception(fn () => PageImportPayload::fromArray(['title' => 'X', 'blocks' => [['block' => 'hero-banner', 'data' => []]]]), 'niepoprawny', 'slug z myślnikiem');
expect_exception(fn () => PageImportPayload::fromArray(['title' => 'X', 'blocks' => [['block' => 'hero', 'data' => ['a', 'b']]]]), 'obiektem', 'data jako lista');
expect_exception(fn () => PageImportPayload::fromFile('/tmp/missing-osf-page.json'), 'Nie można odczytać', 'brak pliku');
expect_exception(fn () => (new PageImporter())->import($payload), 'wp_insert_post', 'importer bez WordPressa');

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
					['name' => 'button1', 'type' => 'link', 'key' => 'field_hero_button1'],
				],
			],
			[
				'name' => 'r_hero',
				'type' => 'repeater',
				'key' => 'field_hero_r_hero',
				'sub_fields' => [
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

echo "\n{$passed} passed, {$failed} failed\n";
exit($failed === 0 ? 0 : 1);
