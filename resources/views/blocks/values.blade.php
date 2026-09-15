<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class(['b-values relative -smt isolate overflow-clip bg-neutral-800 text-white py-14 md:py-24 xl:py-36',
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>
	@if (!empty($g_values['image']['url']))
	<img class="__background absolute inset-0 -z-10 size-full pointer-events-none object-cover object-center" src="{{ $g_values['image']['url'] }}" alt="" loading="lazy" />
	<div class="__overlay absolute inset-0 -z-10 size-full pointer-events-none bg-linear-to-r from-black/90 to-black/95" aria-hidden="true"></div>
	@endif

	<div @class(['__wrapper c-main relative grid grid-cols-1 md:grid-cols-2 items-start gap-8', 'lg:gap-x-24 lg:gap-y-16' => $gap, 'lg:gap-16' => !$gap])>
		<div class="__intro order1 min-w-0 sticky top-20 h-max">
			@if (!empty($g_values['label']))
			<p class="__label flex items-center gap-2.5 mt-0 mb-5 text-lg leading-snug">
				<svg class="text-primary opacity-55 shrink-0" width="30" height="26" viewBox="0 0 30 26" fill="none" aria-hidden="true">
					<path d="M1 14h4L8 4l4 19 4-17 4 14 3-12 3 6h3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
				</svg>
				<span>{{ $g_values['label'] }}</span>
			</p>
			@endif
			@if (!empty($g_values['header']))
			<h2 class="__heading text-h2 text-white leading-none mt-0 mb-5">{{ $g_values['header'] }}</h2>
			@endif
			@if (!empty($g_values['text']))
			<div class="__txt text-neutral-300 text-base md:text-lg leading-snug [&_p]:mt-0 [&_p]:mb-4 [&_p:last-child]:mb-0">{!! $g_values['text'] !!}</div>
			@endif
		</div>

		@if ($values)
		<div class="__items order2 grid min-w-0 gap-4 xl:gap-8">
			@foreach ($values as $value)
			@if (!empty($value['header']) || !empty($value['opis']))
			<article class="__card relative isolate overflow-hidden rounded-3xl bg-secondary-800 p-6 md:px-8 md:py-7 min-h-36 flex items-start">
				@if (!empty($value['icon']['url']))
				<img class="__icon absolute right-0 top-1/2 -translate-y-1/2 size-32 md:size-40 object-contain opacity-40 pointer-events-none" src="{{ $value['icon']['url'] }}" alt="" loading="lazy" />
				@endif
				<div class="__content relative w-full">
					@if (!empty($value['header']))
					<h3 class="__title text-h6 text-white leading-tight mt-0 mb-3">{{ $value['header'] }}</h3>
					@endif
					@if (!empty($value['opis']))
					<div class="__description text-neutral-300 text-base leading-normal wrap-anywhere">{!! wp_kses_post($value['opis']) !!}</div>
					@endif
				</div>
			</article>
			@endif
			@endforeach
		</div>
		@endif
	</div>
</section>