@php

$hasImage1 = !empty($g_image['image']);
$hasImage2 = !empty($g_image['image2']);
$gridClass = ($hasImage1 && $hasImage2) ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1';
$imageClass = ($hasImage1 && $hasImage2) ? 'img-l' : 'img-xl';
@endphp

<!--- image -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-image relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main grid {{ $gridClass }} items-center gap-8">

		@if ($hasImage1)
		<img data-gsap-element="image" class="object-cover w-full __img {{ $imageClass }} order1" src="{{ $g_image['image']['url'] }}" alt="{{ $g_image['image']['alt'] ?? '' }}">
		@endif

		@if ($hasImage2)
		<img data-gsap-element="image" class="object-cover w-full __img {{ $imageClass }} order1" src="{{ $g_image['image2']['url'] }}" alt="{{ $g_image['image2']['alt'] ?? '' }}">
		@endif

	</div>

</section>