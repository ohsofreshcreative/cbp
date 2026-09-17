<!--- reach -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-reach relative' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__hero relative isolate overflow-hidden">
		@if (!empty($g_reach['image']['url']))
		<figure class="__img absolute inset-0 m-0">
			<img src="{{ $g_reach['image']['url'] }}" alt="{{ $g_reach['image']['alt'] ?? '' }}" class="size-full object-cover" data-gsap-element="img">
		</figure>
		@endif
		<div class="__veil absolute inset-0 z-1 pointer-events-none bg-black/50" aria-hidden="true"></div>
		<div class="__wrapper c-main relative z-2">
			@if (!empty($g_reach['header']))
			<div data-gsap-element="header" class="__header text-center text-white">
				{!! $g_reach['header'] !!}
			</div>
			@endif
		</div>
	</div>

	<div class="__band relative bg-white">
		<div class="__wrapper c-main relative z-2 md:grid md:grid-cols-2">
			<div class="__content">
				@if (!empty($g_reach['subheader']))
				<p data-gsap-element="header" class="__subheader">{{ $g_reach['subheader'] }}</p>
				@endif
				@if (!empty($g_reach['text']))
				<div data-gsap-element="txt" class="__txt">
					{!! $g_reach['text'] !!}
				</div>
				@endif
			</div>
			@if (!empty($g_reach['button1']['url']))
			<div class="__cta flex items-center justify-center">
				<div class="__pill bg-primary">
					<x-button
						:href="$g_reach['button1']['url']"
						variant="white"
						data-gsap-element="btn">
						{{ $g_reach['button1']['title'] }}
					</x-button>
				</div>
			</div>
			@endif
		</div>
	</div>
</section>
