<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Tiles extends Block
{
	public $name = 'Tiles';
	public $description = 'tiles';
	public $slug = 'tiles';
	public $category = 'formatting';
	public $icon = 'screenoptions';
	public $keywords = ['tiles', 'kafelki'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => true,
		'jsx' => true,
		'anchor' => true,
		'customClassName' => true,
	];

	public function fields()
	{
		$tiles = new FieldsBuilder('tiles');

		$tiles
			->setLocation('block', '==', 'acf/tiles')
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_tiles', ['label' => ''])
			->addText('label', [
				'label' => 'Etykieta',
			])
			->addText('header', [
				'label' => 'Nagłówek',
			])
			->addImage('image', [
				'label' => 'Obraz',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->addText('caption', [
				'label' => 'Podpis zdjęcia',
			])
			->endGroup()

			->addTab('Kafelki', ['placement' => 'top'])
			->addRepeater('r_tiles', [
				'label' => 'Kafelki',
				'layout' => 'table',
				'button_label' => 'Dodaj kafelek',
			])
			->addText('header', [
				'label' => 'Nagłówek',
			])
			->addWysiwyg('text', [
				'label' => 'Treść',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addImage('icon', [
				'label' => 'Ikona',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->endRepeater()

			->addTab('Ustawienia bloku', ['placement' => 'top'])
			->addText('section_id', [
				'label' => 'ID',
			])
			->addText('section_class', [
				'label' => 'Dodatkowe klasy CSS',
			])
			->addTrueFalse('flip', [
				'label' => 'Odwrotna kolejność',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addTrueFalse('wide', [
				'label' => 'Szeroka kolumna',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addTrueFalse('nomt', [
				'label' => 'Usunięcie marginesu górnego',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addTrueFalse('gap', [
				'label' => 'Większy odstęp',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addSelect('background', [
				'label' => 'Kolor tła',
				'choices' => [
					'none' => 'Brak (domyślne)',
					'section-white' => 'Białe',
					'section-light' => 'Jasne',
					'section-gray' => 'Szare',
					'section-brand' => 'Marki',
					'section-gradient' => 'Gradient',
					'section-dark' => 'Ciemne',
				],
				'default_value' => 'section-dark',
				'ui' => 0,
				'allow_null' => 0,
			]);

		return $tiles;
	}

	public function with(): array
	{
		$fields = [
			'g_tiles' => get_field('g_tiles') ?: [],
			'r_tiles' => get_field('r_tiles') ?: [],

			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),

			'flip' => (bool) get_field('flip'),
			'wide' => (bool) get_field('wide'),
			'nomt' => (bool) get_field('nomt'),
			'gap' => (bool) get_field('gap'),

			'background' => get_field('background') ?: get_field('default_block_background', 'option') ?: 'none',
		];

		$fields['sectionClass'] = SectionClasses::fromMap($fields, [
			'flip' => 'order-flip',
			'wide' => 'wide',
			'nomt' => '!mt-0',
			'gap' => 'wider-gap',
		]);

		return $fields;
	}
}
