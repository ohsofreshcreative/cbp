<!--- offers -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-offers relative -smt py-14 md:py-24' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_offers['header']))
		<h2 data-gsap-element="header" class="text-h3 m-header">{{ $g_offers['header'] }}</h2>
		@endif

		@if (!empty($items))
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			@foreach ($items as $item)
			<a data-gsap-element="card" href="{{ $item['url'] }}" class="__card relative flex flex-col radius bg-white b-shadow p-8">
				<div class="flex items-start justify-between gap-4">
					@if (!empty($item['title']))
					<p class="text-h6 mt-0 mb-3">{{ $item['title'] }}</p>
					@endif

					@if (!empty($item['icon']['url']))
					<img src="{{ $item['icon']['url'] }}" alt="{{ $item['icon']['alt'] ?? '' }}" class="shrink-0 object-contain">
					@else
					<svg class="text-primary shrink-0 mt-1" width="72" height="28" viewBox="0 0 30 26" fill="none" aria-hidden="true">
						<path d="M1 14h4L8 4l4 19 4-17 4 14 3-12 3 6h3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
					</svg>
					@endif
				</div>

				@if (!empty($item['excerpt']))
				<div class="__txt text-secondary-400">{{ $item['excerpt'] }}</div>
				@endif
			</a>
			@endforeach
		</div>
		@elseif (!empty($is_preview))
		<p>Wybierz kategorię oferty, aby wyświetlić wpisy.</p>
		@endif
	</div>
</section>
