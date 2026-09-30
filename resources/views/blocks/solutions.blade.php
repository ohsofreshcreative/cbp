<!--- solutions -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-solutions relative -smt isolate overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if ($bgshape)
	<img data-gsap-element="shape" class="__decoration pointer-events-none absolute top-1/4 right-0 translate-x-1/3 w-2/6 text-primary opacity-25 md:opacity-60" src="{{ get_template_directory_uri() }}/resources/images/shape-stroke.svg" alt="ksztalt w tle" />
	@endif

	<div class="__wrapper c-main relative z-10">
		@if (!empty($g_solutions['header']))
		<h2 data-gsap-element="header" class="">{{ $g_solutions['header'] }}</h2>
		@endif

		@if (!empty($r_solutions))
		@php
		$itemCount = count($r_solutions);
		$gridClass = 'grid-cols-1';
		if ($itemCount === 2) $gridClass = 'grid-cols-1 md:grid-cols-2';
		if ($itemCount === 3) $gridClass = 'grid-cols-1 md:grid-cols-3';
		if ($itemCount >= 4) $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
		@endphp

		<div class="__cards grid {{ $gridClass }} gap-8 mt-10">
			@foreach ($r_solutions as $item)
			<article data-gsap-element="card" class="__card relative radius bg-secondary-700 p-8">
				@if (!empty($item['header']))
				<p data-gsap-element="header" class="text-h4 text-white">{{ $item['header'] }}</p>
				@endif
				@if (!empty($item['text']))
				<div data-gsap-element="txt" class="__txt [&_p]:text-base! [&_strong]:text-primary [&_b]:text-primary [&_strong]:text-xl [&_b]:text-xl mt-6">
					{!! preg_replace('/<br\s*\/?>/i', ' ', $item['text']) !!}
				</div>
				@endif
			</article>
			@endforeach
		</div>
		@endif
	</div>
</section>
