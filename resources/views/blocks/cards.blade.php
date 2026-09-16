<!--- cards --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-cards relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__top text-center">
			@if (!empty($g_cards['header']))
			<h2 data-gsap-element="header" class="m-header">{{ strip_tags($g_cards['header']) }}</h2>
			@endif
			@if (!empty($g_cards['text']))
			<p data-gsap-element="text">{{ $g_cards['text'] }}</p>
			@endif
		</div>

		@if (!empty($r_cards))
		@php
		$itemCount = count($r_cards);
		$gridCols = 1;
		if ($itemCount == 2) $gridCols = 2;
		if ($itemCount == 3) $gridCols = 3;
		if ($itemCount >= 4) $gridCols = 4;
		$gridClass = $gridCols > 1 ? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-' . $gridCols : 'grid-cols-1';
		@endphp

		<div class="grid {{ $gridClass }} gap-8">
			@foreach ($r_cards as $item)
			<div data-gsap-element="card" class="__card relative isolate overflow-hidden radius bg-secondary-800 p-8">
				@if (!empty($item['title']))
				<p>{{ $item['title'] }}</p>
				@endif
				@if (!empty($item['text']))
				<p>{{ $item['text'] }}</p>
				@endif
				@if (!empty($item['image']['url']))
				<img class="__icon absolute right-0 bottom-0 object-contain pointer-events-none" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" />
				@endif
			</div>
			@endforeach
		</div>
		@endif

	</div>

</section>
