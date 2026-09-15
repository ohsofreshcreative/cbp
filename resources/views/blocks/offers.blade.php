<!--- offers -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-offers relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_offers['header']))
		<h2 data-gsap-element="header" class="text-h3 m-header text-secondary-900">{{ $g_offers['header'] }}</h2>
		@endif

		@if (!empty($items))
		<div class="__grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			@foreach ($items as $item)
			<a data-gsap-element="card" href="{{ $item['url'] }}" class="__card relative flex items-start justify-between gap-4 radius bg-white p-6 md:p-8">
				<div class="min-w-0">
					@if (!empty($item['title']))
					<p class="text-h7 mt-0 mb-3 text-secondary-900">{{ $item['title'] }}</p>
					@endif
					<span class="__more inline-flex items-center gap-1 text-secondary-400">
						{{ $link_label }}
						<svg width="14" height="10" viewBox="0 0 18 11" fill="none" aria-hidden="true">
							<path d="M1 5.5h16m0 0L12.5 1M17 5.5 12.5 10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
				</div>

				@if (!empty($item['icon']['url']))
				<img src="{{ $item['icon']['url'] }}" alt="{{ $item['icon']['alt'] ?? '' }}" class="__icon shrink-0 object-contain">
				@else
				<x-icon.ekg class="__ekg text-primary shrink-0 w-16 md:w-20 mt-1" />
				@endif
			</a>
			@endforeach
		</div>
		@elseif (!empty($is_preview))
		<p>Wybierz kategorię oferty, aby wyświetlić wpisy.</p>
		@endif
	</div>
</section>
