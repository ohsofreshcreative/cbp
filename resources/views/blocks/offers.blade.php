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
				<a data-gsap-element="card" href="{{ $item['url'] }}" class="__card relative overflow-hidden flex flex-col items-start gap-8 radius bg-white p-8">
					@if (!empty($item['title']))
					<p class="__title relative z-10">{{ $item['title'] }}</p>
					@endif
					<span class="__more relative z-10 inline-flex items-center">
						{{ $link_label }}
						<svg width="14" height="10" viewBox="0 0 18 11" fill="none" aria-hidden="true">
							<path d="M1 5.5h16m0 0L12.5 1M17 5.5 12.5 10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>

					@if (!empty($item['icon']['url']))
					<img src="{{ $item['icon']['url'] }}" alt="" class="__icon absolute right-0 bottom-0 object-contain object-right-bottom pointer-events-none" width="115" height="92">
					@else
					<svg class="__ekg absolute right-0 bottom-0 text-primary pointer-events-none" width="115" height="92" viewBox="0 0 115 92" fill="none" aria-hidden="true">
						<path opacity="0.6" fill="currentColor" d="M77.6888 92C76.0819 92 74.7036 90.834 74.4308 89.2299L66.6915 44.1502L59.4901 68.8215C59.0626 70.2771 57.7359 71.2648 56.2248 71.2203C54.7286 71.1757 53.4387 70.1211 53.0996 68.6507L45.0212 34.102L38.1368 75.706C37.8641 77.3324 36.4636 78.5281 34.8346 78.4984C33.2057 78.4761 31.8273 77.2581 31.6062 75.6243L23.8005 19.5384L16.2675 46.4525C15.8621 47.8933 14.5648 48.8884 13.0833 48.8884H3.30951C1.48154 48.8884 0 47.3882 0 45.5464C0 43.7046 1.48154 42.2045 3.30951 42.2045H10.5919L21.7072 2.4423C22.1347 0.919843 23.5499 -0.0901783 25.1199 0.00636783C26.6825 0.110341 27.9503 1.31345 28.1714 2.87305L35.1663 53.1587L41.2104 16.6197C41.4684 15.0527 42.7878 13.8867 44.3651 13.8273C45.9572 13.7902 47.3356 14.8522 47.6968 16.3969L56.6818 54.8297L64.3696 28.5023C64.8045 27.0096 66.2123 26.0218 67.7307 26.111C69.2639 26.2001 70.539 27.3438 70.797 28.8737L77.3128 66.8386L84.7427 12.0375C84.949 10.515 86.1505 9.33421 87.6615 9.17083C89.1799 9.00744 90.5951 9.89863 91.1258 11.3394L102.263 41.9742H111.69C113.518 41.9742 115 43.4744 115 45.3162C115 47.158 113.518 48.6582 111.69 48.6582H99.9561C98.5704 48.6582 97.3321 47.7819 96.853 46.4673L89.4969 26.2446L80.9762 89.0962C80.755 90.73 79.3914 91.9554 77.7551 91.9851C77.733 91.9851 77.7182 91.9851 77.6961 91.9851L77.6888 92Z" />
					</svg>
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
