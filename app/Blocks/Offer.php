<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Offer extends Block
{
    public $name = 'Offer';
    public $description = 'Obszary działalności — kategorie i wpisy ofert.';
    public $slug = 'offer';
    public $category = 'formatting';
    public $icon = 'index-card';
    public $keywords = ['oferta', 'obszary', 'kategorie'];
    public $mode = 'edit';
    public $supports = ['align' => false, 'mode' => true, 'jsx' => true];

    public function fields()
    {
        $offer = new FieldsBuilder('offer');
        $offer
            ->setLocation('block', '==', 'acf/offer')
            ->addTab('Treści', ['placement' => 'top'])
            ->addGroup('g_offer', ['label' => 'Treść'])
            ->addText('header', ['label' => 'Nagłówek', 'default_value' => 'Obszary działalności'])
            ->addMessage('Źródło treści', 'Zakładki pochodzą z kategorii w sekcji Oferta. Ustaw tam zdjęcie, opis i kolejność. Lista zawiera opublikowane oferty przypisane bezpośrednio do kategorii, sortowane według pola Kolejność, następnie tytułu. Puste kategorie są pomijane.')
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
				'default_value' => 'none',
				'ui' => 0, // Ulepszony interfejs
				'allow_null' => 0,
			]);

        return $offer;
    }

    public function with(): array
    {
        $terms = get_terms(['taxonomy' => 'offer_category', 'hide_empty' => true]);
        $categories = [];
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $posts = get_posts([
                    'post_type' => 'offer',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'],
                    'tax_query' => [[
                        'taxonomy' => 'offer_category',
                        'field' => 'term_id',
                        'terms' => $term->term_id,
                        'include_children' => false,
                    ]],
                ]);
                if (!$posts) {
                    continue;
                }
                $categories[] = [
                    'name' => $term->name,
                    'description' => term_description($term->term_id, 'offer_category'),
                    'image' => get_field('offer_category_image', $term),
                    'order' => (int) get_field('offer_category_order', $term),
                    'posts' => $posts,
                ];
            }
        }
        usort($categories, fn ($a, $b) => $a['order'] <=> $b['order'] ?: strnatcasecmp($a['name'], $b['name']));
        $fields = [
            'g_offer' => get_field('g_offer') ?: [],
            'categories' => $categories,
            'offer_id' => wp_unique_id('offer-'),
            'section_id' => get_field('section_id'),
            'section_class' => get_field('section_class'),
            'flip' => (bool) get_field('flip'),
            'wide' => (bool) get_field('wide'),
            'nomt' => (bool) get_field('nomt'),
            'gap' => (bool) get_field('gap'),
            'background' => get_field('background') ?: 'none',
        ];
        $fields['sectionClass'] = SectionClasses::fromMap($fields, [
            'flip' => 'order-flip', 'wide' => 'wide', 'nomt' => '!mt-0', 'gap' => 'wider-gap',
        ]);
        return $fields;
    }
}
