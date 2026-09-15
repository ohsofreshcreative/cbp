<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ThemeSettings extends Field
{
	public function fields(): array
	{
		$theme = new FieldsBuilder('theme_settings');

		$theme
			->setLocation('options_page', '==', 'theme-settings')
			->addTab('Logo', ['placement' => 'top'])
			->addImage('logo', [
				'label' => 'Logo',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'library' => 'all',
			])
			->addImage('logo_footer', [
				'label' => 'Logo Stopka',
				'return_format' => 'array', 
				'preview_size' => 'medium',
				'library' => 'all',
			])

			->addTab('Dane kontaktowe (Stopka)', ['placement' => 'top'])
			->addGroup('footer_contact', ['label' => 'Dane kontaktowe stopki'])

			->addWysiwyg('address', [
				'label' => 'Adres / Dane firmy',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addText('phone', [
				'label' => 'Numer telefonu',
			])
			->addText('email', [
				'label' => 'Adres E-mail',
			])
            ->endGroup()
            ->addTab('Wygląd stopki', ['placement' => 'top'])
            ->addImage('footer_decoration', [
                'label' => 'Ilustracja po prawej',
                'instructions' => 'Grafika na przezroczystym tle. Zostanie wyświetlona jako przygaszona dekoracja.',
                'return_format' => 'array', 'preview_size' => 'medium',
            ])
            ->addGroup('footer_social', ['label' => 'Social media'])
            ->addUrl('facebook', ['label' => 'Facebook'])
            ->addUrl('instagram', ['label' => 'Instagram'])
            ->addUrl('youtube', ['label' => 'YouTube'])
            ->endGroup();

		return [$theme];
	}
}
