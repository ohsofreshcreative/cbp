<!-- hero --->

@php
	$hasTiles = !empty($r_hero);
	$badges = $g_hero['badges'] ?? [];
	$button1 = $g_hero['button1'] ?? null;
	$button2 = $g_hero['button2'] ?? null;
	$hasButton1 = !empty($button1['url']);
	$hasButton2 = !empty($button2['url']);
	$button1Variant = (!$hasTiles && !$hasButton2) ? 'primary' : 'secondary';
@endphp

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-hero relative flex flex-col overflow-visible' ,
	$hasTiles ? 'h-screen -spt' : '-menu-pt',
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if (!empty($g_hero['video']))
	<video class="absolute inset-0 w-full h-full object-cover z-0" autoplay loop muted playsinline>
		<source src="{{ $g_hero['video'] }}" type="video/mp4">
	</video>
	@elseif(!empty($g_hero['image']))
	<figure @class(['absolute z-0 m-0 overflow-hidden', $hasTiles ? 'inset-0 w-full h-full' : 'inset-y-0 right-0 h-full w-full md:w-[64%]'])>
		<picture class="w-full h-full">
			<img src="{{ $g_hero['image']['url'] }}" alt="{{ $g_hero['image']['alt'] }}" class="w-full h-full object-cover" />
		</picture>
	</figure>
	@endif

	@if (!empty($g_hero['video']) || !empty($g_hero['image']))
	<div @class(['absolute inset-0 z-1 pointer-events-none', !$hasTiles ? '__veil' : '']) @if($hasTiles) style="background: linear-gradient(90deg, #000 0%, rgba(0, 0, 0, 0.20) 100%);" @endif></div>
	@endif

	<div @class([
		'__wrapper relative z-10',
		$hasTiles ? 'c-wide grid grid-cols-1 md:grid-cols-2 justify-end items-end !mt-auto gap-20 md:gap-10 pb-40' : 'c-main flex flex-col justify-center py-10 md:pt-10 md:pb-36',
	])>
		<div @class(['__content relative z-20', $hasTiles ? 'w-full md:w-8/12' : 'w-full'])>
			@if (!empty($badges) && !$hasTiles)
			<div class="__badges mb-6 flex flex-wrap gap-3 text-primary">
				@foreach ($badges as $badge)
					@if (!empty($badge['text']))
					<div class="__badge flex max-w-full items-center gap-3 rounded-full border border-primary px-5 py-2">
						@if (!empty($badge['icon']['url']))
						<img src="{{ $badge['icon']['url'] }}" alt="" class="h-4 w-4 shrink-0 object-contain" />
						@endif
						<span class="min-w-0 break-words">{{ $badge['text'] }}</span>
					</div>
					@endif
				@endforeach
			</div>
			@endif

			<div data-gsap-element="header" class="text-h2 [&_p]:font-header text-white">
				{!! $g_hero['header'] !!}
			</div>
			@if (!empty($g_hero['text']))
			<div data-gsap-element="text" class="text-white mt-2">
				{!! $g_hero['text'] !!}
			</div>
			@endif

			@if (!empty($badges) && $hasTiles)
			<div class="__badges mt-8 flex flex-wrap gap-3 text-primary">
				@foreach ($badges as $badge)
					@if (!empty($badge['text']))
					<div class="__badge flex max-w-full items-center gap-3 rounded-full border border-primary px-5 py-2 text-sm">
						@if (!empty($badge['icon']['url']))
						<img src="{{ $badge['icon']['url'] }}" alt="" class="h-6 w-6 shrink-0 object-contain" />
						@endif
						<span class="min-w-0 break-words">{{ $badge['text'] }}</span>
					</div>
					@endif
				@endforeach
			</div>
			@endif

			@if ($hasButton1 || $hasButton2)
			<div class="inline-buttons m-btn">
				@if ($hasButton1)
				<x-button
					:href="$button1['url']"
					:variant="$button1Variant"
					data-gsap-element="btn">
					{{ $button1['title'] }}
				</x-button>
				@endif

				@if ($hasButton2)
				<x-button
					:href="$button2['url']"
					variant="white"
					data-gsap-element="btn">
					{{ $button2['title'] }}
				</x-button>
				@endif
			</div>
			@endif
		</div>

		@if ($hasTiles)
		<div data-gsap-element="tiles" class="__tiles grid grid-cols-2 gap-4">
			@foreach ($r_hero as $tile)
			<a
				href="{{ $tile['button']['url'] ?? '#' }}"
				@if (!empty($tile['button']['target'])) target="{{ $tile['button']['target'] }}" @endif
				class="__tile group relative flex flex-col justify-end aspect-square rounded-3xl border border-primary overflow-hidden b-glow">
				@if (!empty($tile['image']['url']))
				<img src="{{ $tile['image']['url'] }}" alt="{{ $tile['image']['alt'] ?? '' }}" class="absolute inset-0 w-full h-full object-cover" />
				@endif
				<div class="absolute inset-0" style="background: linear-gradient(0deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 85%);"></div>

				<div class="relative z-10 p-6 flex flex-col gap-6">
					<span class="text-white text-h7 font-header">{{ $tile['title'] }}</span>

					<span class="__arrow rounded-full bg-primary group-hover:bg-white group-hover:rotate-45 h-14 w-14 flex items-center justify-center shrink-0 transition-all duration-300">
						<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M4 14L14 4M14 4H6M14 4V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-secondary" />
						</svg>
					</span>
				</div>
			</a>
			@endforeach
		</div>
		@endif
	</div>

</section>
