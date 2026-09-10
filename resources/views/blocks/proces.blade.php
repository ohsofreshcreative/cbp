<!--- proces --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-proces relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="">
			<div class="__top">
				@if (!empty($g_proces['header']))
				<h3 data-gsap-element="header" class=" m-header">{{ strip_tags($g_proces['header']) }}</h3>
				@endif
				<div data-gsap-element="txt" class="">{{ strip_tags($g_proces['txt']) }}</div>
			</div>
		</div>

		@if (!empty($r_proces))
		@php
		$repeater_count = count($r_proces);
		$grid_class = 'lg:grid-cols-4'; // Domyślna klasa
		if ($repeater_count === 3) {
		$grid_class = 'lg:grid-cols-3';
		}
		@endphp
		<div class="__repeater gap-8 grid grid-cols-1 md:grid-cols-2 {{ $grid_class }} mt-16">

			@foreach ($r_proces as $item)
			<div data-gsap-element="stagger" class="flex flex-col bg-primary radius p-6">
				<div class="relative z-20">
					<div class="text-h2">{{ $item['number'] }}</div>
					<img class="" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" />

					<p class="text-h6 mt-4">{{ $item['title'] }}</p>
					<p class="mt-2">{!! $item['txt'] !!}</p>
				</div>
			</div>
			@endforeach
		</div>
		<div class="__line absolute bg-primary z-0 origin-left scale-x-0"></div>
		@endif
	</div>

</section>