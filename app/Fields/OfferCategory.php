<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class OfferCategory extends Field
{
    public function fields(): array
    {
        $fields = new FieldsBuilder('offer_category_fields', ['title' => 'Wygląd kategorii w bloku Offer']);
        $fields
            ->setLocation('taxonomy', '==', 'offer_category')
            ->addImage('offer_category_image', [
                'label' => 'Zdjęcie kategorii',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addNumber('offer_category_order', [
                'label' => 'Kolejność zakładki',
                'instructions' => 'Mniejsza liczba wyświetla kategorię wcześniej. Treść obok zdjęcia uzupełnij w polu Opis kategorii.',
                'default_value' => 0,
                'step' => 1,
            ]);
        return [$fields];
    }
}
