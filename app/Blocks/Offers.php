<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Offers extends Block
{
	public $name = 'Oferty';
	public $description = 'offers';
	public $slug = 'offers';
	public $category = 'formatting';
	public $icon = 'index-card';
	public $keywords = ['oferta', 'oferty', 'kategoria'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => true,
		'jsx' => true,
	];

	public function fields()
	{
		$offers = new FieldsBuilder('offers');

		$offers
			->setLocation('block', '==', 'acf/offers') // ważne!
			/*--- TAB #1 ---*/
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_offers', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->addTaxonomy('offer_category', [
				'label' => 'Kategoria oferty',
				'instructions' => 'Wybierz kategorię CPT Oferta. Blok wyświetli opublikowane wpisy z tej kategorii.',
				'taxonomy' => 'offer_category',
				'field_type' => 'select',
				'allow_null' => 0,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'multiple' => 0,
			])
			->endGroup()

			/*--- USTAWIENIA BLOKU ---*/
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
				'default_value' => 'section-white',
				'ui' => 0,
				'allow_null' => 0,
			]);

		return $offers;
	}

	public function with(): array
	{
		$g_offers = get_field('g_offers') ?: [];
		$term_id = $g_offers['offer_category'] ?? null;

		if (is_array($term_id)) {
			$term_id = $term_id[0] ?? null;
		}

		$posts = [];

		if (!empty($term_id)) {
			$posts = get_posts([
				'post_type' => 'offer',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'],
				'tax_query' => [[
					'taxonomy' => 'offer_category',
					'field' => 'term_id',
					'terms' => (int) $term_id,
					'include_children' => false,
				]],
			]);
		}

		$items = [];

		foreach ($posts as $post) {
			$items[] = [
				'id' => $post->ID,
				'title' => get_the_title($post),
				'url' => get_permalink($post),
				'excerpt' => has_excerpt($post) ? $post->post_excerpt : wp_trim_words(wp_strip_all_tags($post->post_content), 16, ''),
				'icon' => get_field('offer_icon', $post->ID),
			];
		}

		$fields = [
			'g_offers' => $g_offers,
			'items' => $items,

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
