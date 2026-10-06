@php
$previewSettings = [];
$viewVariables = get_defined_vars();

foreach ($viewVariables as $name => $value) {
	if (! is_bool($value) || ! $value || ! function_exists('get_field_object')) {
		continue;
	}

	$fieldName = $name === 'bullets' ? 'nolist' : $name;
	$field = get_field_object($fieldName, false, false, false);

	if (($field['type'] ?? null) === 'true_false') {
		$previewSettings[] = $field['label'] ?? ucfirst(str_replace('_', ' ', $fieldName));
	}
}

$backgroundChoices = \App\Support\SectionBackgrounds::choices();
$backgroundValue = $background ?? 'none';

if ($backgroundValue !== 'none' && isset($backgroundChoices[$backgroundValue])) {
	$previewSettings[] = 'Tło: ' . $backgroundChoices[$backgroundValue];
}
@endphp

@if (!empty($previewSettings))
<div class="acf-preview__settings" aria-label="Wybrane ustawienia bloku">
	@foreach ($previewSettings as $setting)
	<span @class([
		'acf-preview__setting',
		'acf-preview__setting--background' => str_starts_with($setting, 'Tło:'),
		'acf-preview__setting--nomt' => in_array($setting, ['Brak marginesu górnego', 'Usunięcie marginesu górnego'], true),
	])>{{ $setting }}</span>
	@endforeach
</div>
@endif
