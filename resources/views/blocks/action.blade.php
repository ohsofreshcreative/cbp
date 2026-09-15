<!--- action -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-action relative -smt -spt -spb overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if (!empty($g_action['image']))
	<figure class="absolute inset-0 w-full h-full z-0 m-0">
		<img src="{{ $g_action['image']['url'] }}" alt="{{ $g_action['image']['alt'] ?? '' }}" class="w-full h-full object-cover">
	</figure>
	<div class="absolute inset-0 z-1 pointer-events-none bg-black/50"></div>
	@endif

	<div class="__wrapper c-main relative z-10">
		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20">
			<div class="__content order1">
				@if (!empty($g_action['header']))
				<h2 data-gsap-element="header" class="m-header text-white">{{ $g_action['header'] }}</h2>
				@endif

				@if (!empty($g_action['text']))
				<div data-gsap-element="txt" class="__txt text-white">
					{!! $g_action['text'] !!}
				</div>
				@endif
			</div>

			@if (!empty($g_action['button1']['url']))
			<div class="inline-buttons m-btn order2">
				<x-button
					:href="$g_action['button1']['url']"
					variant="primary"
					data-gsap-element="btn">
					{{ $g_action['button1']['title'] }}
				</x-button>
			</div>
			@endif
		</div>
	</div>
</section>
