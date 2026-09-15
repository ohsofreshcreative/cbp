<?php

namespace App\Options;

use Log1x\AcfComposer\Options;
use StoutLogic\AcfBuilder\FieldsBuilder;

class OCertificates extends Options
{
    public $name = 'Certyfikaty';
    public $slug = 'ocertificates';
    public $title = 'Certyfikaty i uprawnienia';
    public $position = 103;
    public $capability = 'edit_posts';
    public $redirect = false;

    public function fields(): FieldsBuilder
    {
        $certificates = new FieldsBuilder('ocertificates');
        $certificates
            ->addTab('Certyfikaty', ['placement' => 'top'])
            ->addGroup('g_certificates', ['label' => ''])
            ->addText('header', [
                'label' => 'Nagłówek',
                'default_value' => 'Certyfikaty i uprawnienia',
            ])
            ->addTextarea('text', [
                'label' => 'Opis',
                'rows' => 3,
                'default_value' => 'Poznaj kwalifikacje i dokumenty potwierdzające kompetencje ekspertki.',
            ])
            ->addGallery('gallery', [
                'label' => 'Certyfikaty JPG',
                'instructions' => 'Dodaj pliki JPG i przeciągnij, aby ustawić kolejność. Miniatura i powiększenie korzystają z tego samego zdjęcia. Uzupełnij tekst alternatywny i opcjonalny podpis w bibliotece mediów.',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg',
                'library' => 'all',
                'min' => 0,
            ])
            ->endGroup();
        return $certificates;
    }
}
