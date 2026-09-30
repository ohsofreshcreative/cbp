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
		<div class="__top w-full md:w-2/3">
			@if (!empty($g_tiles['label']))
			<p data-gsap-element="header" class="__label flex items-center gap-2.5 mt-0 mb-5 text-lg leading-snug">
				<svg class="text-primary opacity-55 shrink-0" width="30" height="26" viewBox="0 0 30 26" fill="none" aria-hidden="true">
					<path d="M1 14h4L8 4l4 19 4-17 4 14 3-12 3 6h3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
				</svg>
				<span>{{ $g_tiles['label'] }}</span></p>
			@endif
			@if (!empty($g_tiles['header']))
			<h2 data-gsap-element="header" class="m-header">{{ $g_tiles['header'] }}</h2>
			@endif
		</div>

		@if (!empty($g_tiles['image']['url']))
		<figure class="__img relative m-0 overflow-hidden radius mt-12">
			<img src="{{ $g_tiles['image']['url'] }}" alt="{{ $g_tiles['image']['alt'] ?? '' }}" class="w-full object-cover" data-gsap-element="img">
			@if (!empty($g_tiles['caption']))
			<figcaption class="__caption absolute border border-primary bg-primary-hover rounded-full text-primary bottom-4 left-4 px-4 py-2">{{ $g_tiles['caption'] }}</figcaption>
			@endif
		</figure>
		@endif

		@if (!empty($r_tiles))
		<div class="__cards grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
			@foreach ($r_tiles as $item)
			<article data-gsap-element="card" class="__card relative isolate overflow-hidden radius bg-secondary-800 p-8">
				@if (!empty($item['icon']['url']))
				<img class="__icon absolute pointer-events-none object-contain -right-10" src="{{ $item['icon']['url'] }}" alt="" >
				@endif
				@if (!empty($item['header']))
				<p class="__title relative text-h6">{{ $item['header'] }}</p>
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
