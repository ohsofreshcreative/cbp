<!--- offer -->
<section data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class(['b-offer relative -smt overflow-hidden rounded-t-3xl bg-neutral-50 py-12 text-secondary-700 md:rounded-t-4xl md:py-16 xl:py-26 [&.offer-home]:-mt-16! [&.offer-home]:z-10 [&.wider-gap_[role=tabpanel]]:gap-14',
		$sectionClass => filled($sectionClass),
		$section_class => filled($section_class),
		$background => filled($background) && $background !== 'none',
	])>
	<svg class="__decoration pointer-events-none absolute top-1/4 left-0 w-1/5 min-w-44 text-primary opacity-25 md:opacity-60" viewBox="0 0 370 580" fill="none" aria-hidden="true">
		<path d="M-20 310H48L124 44Q139 15 159 44L208 382L253 136Q271 106 291 136L363 478M-20 352H64Q81 352 86 334L137 158L192 529Q210 560 230 531L277 253L333 481Q339 503 370 500" stroke="currentColor" stroke-width="3" />
	</svg>
	<div class="__wrapper c-main relative">
		@if (!empty($g_offer['header']))
		<h2 class="__heading text-h2 mb-8">{{ $g_offer['header'] }}</h2>
		@endif
		@if ($categories)
		<div class="__tabs mb-6 flex flex-wrap gap-2 md:mb-10 md:gap-4" role="tablist" aria-label="{{ $g_offer['header'] ?? 'Obszary działalności' }}">
			@foreach ($categories as $category)
			<button type="button" class="__tab cursor-pointer rounded-full border border-dashed border-primary bg-primary-100 px-4 py-2.5 text-sm leading-snug text-primary-900 hover:border-solid hover:bg-primary aria-selected:border-solid aria-selected:bg-primary md:px-8 md:py-3 md:text-base" role="tab" id="{{ $offer_id }}-tab-{{ $loop->index }}"
				aria-controls="{{ $offer_id }}-panel-{{ $loop->index }}"
				aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}">{{ $category['name'] }}</button>
			@endforeach
		</div>
		@foreach ($categories as $category)
		<div class="__panel grid grid-cols-1 items-start gap-8 md:grid-cols-2 [&[hidden]]:hidden" role="tabpanel" id="{{ $offer_id }}-panel-{{ $loop->index }}"
			aria-labelledby="{{ $offer_id }}-tab-{{ $loop->index }}" tabindex="0" @if (!$loop->first) hidden @endif>
			@if (!empty($category['image']['url']))
			<figure class="__img order1 aspect-4/3 overflow-hidden rounded-3xl md:aspect-8/7">
				<img class="size-full object-cover grayscale" src="{{ $category['image']['url'] }}" alt="{{ $category['image']['alt'] ?? '' }}" loading="lazy" />
			</figure>
			@endif
			<div class="__content order2 min-w-0 only:col-span-full">
				<h3 class="__title text-h5 mb-4">{{ $category['name'] }}</h3>
				@if (!empty($category['description']))
				<div class="__txt text-lg leading-snug text-secondary-400 [&_p]:mb-3">{!! wp_kses_post($category['description']) !!}</div>
				@endif
				<ul class="__links mt-8 grid gap-4">
					@foreach ($category['posts'] as $offer_post)
					<li class="__item">
						<a class="__link flex items-center justify-between gap-4 rounded-3xl border border-secondary-100 bg-neutral-100 p-4 text-secondary-700! hover:bg-primary-100" href="{{ get_permalink($offer_post) }}">
							<span>{{ get_the_title($offer_post) }}</span>
							<span class="__arrow grid size-9 shrink-0 place-items-center rounded-full bg-primary" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
									<path d="M4 12h16m-7-7 7 7-7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>
						</a>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
		@endforeach
		@elseif (!empty($is_preview))
		<p>Dodaj kategorie i przypisz do nich opublikowane wpisy w sekcji Oferta.</p>
		@endif
	</div>
</section>
