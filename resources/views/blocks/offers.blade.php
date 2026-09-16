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
		<h2 data-gsap-element="header" class="m-header">{{ $g_offers['header'] }}</h2>
		@endif

		@if (!empty($categories))
		@if (count($categories) > 1)
		<div class="__tabs flex flex-wrap gap-2" role="tablist" aria-label="{{ $g_offers['header'] ?? 'Oferta' }}">
			@foreach ($categories as $category)
			<button type="button" class="__tab" role="tab" id="{{ $offers_id }}-tab-{{ $loop->index }}"
				aria-controls="{{ $offers_id }}-panel-{{ $loop->index }}"
				aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}">{{ $category['name'] }}</button>
			@endforeach
		</div>
		@endif

		@foreach ($categories as $category)
		<div class="__panel [&[hidden]]:hidden" role="tabpanel" id="{{ $offers_id }}-panel-{{ $loop->index }}"
			aria-labelledby="{{ $offers_id }}-tab-{{ $loop->index }}" tabindex="0" @if (!$loop->first) hidden @endif>
			<div class="__grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				@foreach ($category['items'] as $item)
				<a data-gsap-element="card" href="{{ $item['url'] }}" class="__card relative flex items-start justify-between radius bg-white p-6 md:p-8">
					<div class="min-w-0">
						@if (!empty($item['title']))
						<p class="__title">{{ $item['title'] }}</p>
						@endif
						<span class="__more inline-flex items-center">
							{{ $link_label }}
							<svg width="14" height="10" viewBox="0 0 18 11" fill="none" aria-hidden="true">
								<path d="M1 5.5h16m0 0L12.5 1M17 5.5 12.5 10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>
					</div>

					@if (!empty($item['icon']['url']))
					<img src="{{ $item['icon']['url'] }}" alt="{{ $item['icon']['alt'] ?? '' }}" class="__icon shrink-0 object-contain">
					@else
					<x-icon.ekg class="__ekg text-primary shrink-0" />
					@endif
				</a>
				@endforeach
			</div>
		</div>
		@endforeach
		@elseif (!empty($is_preview))
		<p>Dodaj kategorie i przypisz do nich opublikowane wpisy w sekcji Oferta.</p>
		@endif
	</div>
</section>
