<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Myth extends Block
{
    public $name = 'Myth';
    public $description = 'myth';
    public $slug = 'myth';
    public $category = 'formatting';
    public $icon = 'format-quote';
    public $keywords = ['mity', 'fakty', 'wyjaśnienia'];
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
        $myth = new FieldsBuilder('myth');

        $myth
            ->setLocation('block', '==', 'acf/myth')
            ->addTab('Elementy', ['placement' => 'top'])
            ->addGroup('g_myth', ['label' => 'Treść sekcji'])
            ->addText('header', ['label' => 'Nagłówek', 'default_value' => 'Obalamy mity'])
            ->addTextarea('text', [
                'label' => 'Opis',
                'rows' => 3,
                'default_value' => 'Wokół badań poligraficznych narosło wiele nieporozumień. Sprawdź, co jest faktem, a co jedynie popularnym mitem.',
            ])
            ->endGroup()
            ->addTab('Mity', ['placement' => 'top'])
            ->addRepeater('r_myth', [
                'label' => 'Mity i wyjaśnienia',
                'layout' => 'table',
                'min' => 0,
                'button_label' => 'Dodaj mit',
            ])
            ->addTextarea('myth', [
                'label' => 'Mit',
                'rows' => 3,
                'required' => 1,
            ])
            ->addTextarea('explanation', [
                'label' => 'Wyjaśnienie',
                'rows' => 5,
                'required' => 1,
            ])
            ->endRepeater()

            /*--- USTAWIENIA BLOKU ---*/

            ->addTab('Ustawienia bloku', ['placement' => 'top'])
            ->addText('section_id', ['label' => 'ID'])
            ->addText('section_class', ['label' => 'Dodatkowe klasy CSS'])
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
                'ui' => 0,
                'allow_null' => 0,
            ]);

        return $myth;
    }

    public function with(): array
    {
        $fields = [
            'g_myth' => get_field('g_myth') ?: [],
            'r_myth' => get_field('r_myth') ?: [],

            'section_id' => get_field('section_id'),
            'section_class' => get_field('section_class'),

            'flip' => (bool) get_field('flip'),
            'wide' => (bool) get_field('wide'),
            'nomt' => (bool) get_field('nomt'),
            'gap' => (bool) get_field('gap'),

            'background' => get_field('background') ?: 'none',
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
