<!--- solutions -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-solutions relative -smt isolate overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<x-icon.ekg class="absolute z-0 text-primary pointer-events-none right-0 top-1/2 w-1/3 -translate-y-1/2" />

	<div class="__wrapper c-main relative z-10">
		@if (!empty($g_solutions['header']))
		<h2 data-gsap-element="header" class="m-header">{{ $g_solutions['header'] }}</h2>
		@endif

		@if (!empty($r_solutions))
		@php
		$itemCount = count($r_solutions);
		$gridClass = 'grid-cols-1';
		if ($itemCount === 2) $gridClass = 'grid-cols-1 md:grid-cols-2';
		if ($itemCount === 3) $gridClass = 'grid-cols-1 md:grid-cols-3';
		if ($itemCount >= 4) $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
		@endphp

		<div class="__cards grid {{ $gridClass }} gap-8">
			@foreach ($r_solutions as $item)
			<article data-gsap-element="card" class="__card relative radius bg-secondary-700 p-8">
				@if (!empty($item['header']))
				<h3 data-gsap-element="header" class="text-white">{{ $item['header'] }}</h3>
				@endif
				@if (!empty($item['text']))
				<div data-gsap-element="txt" class="__txt">
					{!! $item['text'] !!}
				</div>
				@endif
			</article>
			@endforeach
		</div>
		@endif
	</div>
</section>
