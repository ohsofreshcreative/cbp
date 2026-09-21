<!--- tiles -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-tiles relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__top">
			@if (!empty($g_tiles['label']))
			<p data-gsap-element="header" class="__label">{{ $g_tiles['label'] }}</p>
			@endif
			@if (!empty($g_tiles['header']))
			<h2 data-gsap-element="header" class="m-header">{{ $g_tiles['header'] }}</h2>
			@endif
		</div>

		@if (!empty($g_tiles['image']['url']))
		<figure class="__img relative m-0 overflow-hidden radius m-img">
			<img src="{{ $g_tiles['image']['url'] }}" alt="{{ $g_tiles['image']['alt'] ?? '' }}" class="w-full object-cover" data-gsap-element="img">
			@if (!empty($g_tiles['caption']))
			<figcaption class="__caption absolute bottom-4 left-4">{{ $g_tiles['caption'] }}</figcaption>
			@endif
		</figure>
		@endif

		@if (!empty($r_tiles))
		<div class="__cards grid grid-cols-1 md:grid-cols-2 gap-8">
			@foreach ($r_tiles as $item)
			<article data-gsap-element="card" class="__card relative isolate overflow-hidden radius bg-secondary-800 p-8">
				@if (!empty($item['icon']['url']))
				<img class="__icon absolute pointer-events-none object-contain" src="{{ $item['icon']['url'] }}" alt="" >
				@endif
				@if (!empty($item['header']))
				<p class="__title relative">{{ $item['header'] }}</p>
				@endif
				@if (!empty($item['text']))
				<div data-gsap-element="txt" class="__txt relative">{!! $item['text'] !!}</div>
				@endif
			</article>
			@endforeach
		</div>
		@endif
	</div>
</section>
