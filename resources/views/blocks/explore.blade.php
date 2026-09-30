<!--- explore -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-explore relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__top pb-10">
			@if (!empty($g_explore['label']))
			<p class="__label flex items-center gap-2 text-white">
				<x-icon.ekg class="w-8 h-7 shrink-0 text-primary-800" aria-hidden="true" />
				<span>{{ $g_explore['label'] }}</span>
			</p>
			@endif
			@if (!empty($g_explore['header']))
			<h2 data-gsap-element="header" class="m-header">{{ $g_explore['header'] }}</h2>
			@endif
		</div>

		<div class="__col grid grid-cols-1 gap-8 lg:grid-cols-4">
			@if (!empty($g_explore['image']['url']))
			<figure class="__img relative m-0 aspect-5/6 w-full self-stretch overflow-hidden radius md:w-1/2 md:justify-self-center lg:w-full">
				<img src="{{ $g_explore['image']['url'] }}" alt="{{ $g_explore['image']['alt'] ?? '' }}" class="absolute inset-0 size-full object-cover" data-gsap-element="img" loading="lazy" decoding="async">
				@if (!empty($g_explore['caption']))
				<figcaption class="__caption absolute bottom-6 left-6 right-6 w-fit rounded-(--btns-radius) border border-primary bg-primary-hover px-4 py-2 text-primary">{{ $g_explore['caption'] }}</figcaption>
				@endif
			</figure>
			@endif

			@if (!empty($r_explore))
			<div class="__cards grid grid-cols-1 auto-rows-fr gap-8 md:grid-cols-2 lg:col-span-3 only:col-span-full">
				@foreach ($r_explore as $item)
				<div data-gsap-element="card" class="__card flex items-center gap-6 radius bg-secondary-700 p-6">
					@if (!empty($item['image']['url']))
					<img src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" class="__logo size-20 shrink-0 object-contain sm:size-28 rounded-full" loading="lazy" decoding="async" width="112" height="112">
					@endif
					<div class="__inside min-w-0">
						@if (!empty($item['header']))
						@php
							$titleParts = preg_split('/(?=\d)/u', $item['header'], 2);
						@endphp
						<p class="__title text-h7">
							{{ $titleParts[0] }}@if (isset($titleParts[1]))<span class="text-primary!">{{ $titleParts[1] }}</span>@endif
						</p>
						@endif
						@if (!empty($item['text']))
						<div class="__txt [&_p]:text-secondary-100!">{!! $item['text'] !!}</div>
						@endif
					</div>
				</div>
				@endforeach
			</div>
			@endif
		</div>
	</div>
</section>
