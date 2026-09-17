<!--- cta --->
@php
$g_octa = $g_octa ?? get_field('g_octa', 'option');
$form = $form ?? true;
$sectionClass = $sectionClass ?? '-smt';
$section_id = $section_id ?? '';
$section_class = $section_class ?? '';
$background = $background ?? 'none';
$gap = $gap ?? false;
@endphp
@if (!empty($g_octa))
@include('blocks.cta')
@endif
