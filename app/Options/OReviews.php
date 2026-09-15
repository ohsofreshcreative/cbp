<?php

namespace App\Options;

use Log1x\AcfComposer\Options;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Oreviews extends Options
{
	public $name = 'Opinie';
	public $slug = 'oreviews';
	public $title = 'Opinie';
	public $position = 101;
	public $capability = 'edit_posts';
	public $redirect = false;

	public function fields(): FieldsBuilder
	{
		$oreviews = new FieldsBuilder('oreviews');

		$oreviews
			->addText('header', ['label' => 'Nagłówek', 'default_value' => 'Zaufanie, które budujemy każdą sprawą'])
            ->addNumber('reviews_rating', [
                'label' => 'Średnia ocen Google',
                'instructions' => 'Wpisz aktualną średnią z profilu Google. Puste pole ukrywa ocenę.',
                'min' => 0, 'max' => 5, 'step' => 0.1,
            ])
            ->addUrl('reviews_google_url', ['label' => 'Link do opinii Google'])
			->addRepeater('r_reviews', [
				'label'        => 'Opinie',
				'layout'       => 'table',
				'min'          => 1,
				'max'          => 50,
				'button_label' => 'Dodaj opinię',
			])
			->addText('header', [
				'label' => 'Nagłówek',
			])
			->addTextarea('txt', [
				'label'     => 'Treść opinii',
				'rows'      => 4,
				'new_lines' => 'br',
			])
			->addImage('image', [
				'label' => 'Avatar (opcjonalny)',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->addText('name', [
				'label' => 'Imię i nazwisko',
			])
			->addText('position', [
				'label' => 'Podpis pod nazwiskiem (np. 1 rok temu)',
			])
			->endRepeater();

		return $oreviews;
	}
}
