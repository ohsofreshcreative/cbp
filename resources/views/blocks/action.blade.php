<!--- action -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-action relative -smt isolate overflow-hidden py-16 md:py-24' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if (!empty($g_action['image']['url']))
	<figure class="absolute inset-0 z-0 m-0 size-full">
		<img src="{{ $g_action['image']['url'] }}" alt="{{ $g_action['image']['alt'] ?? '' }}" class="size-full object-cover object-right">
	</figure>
	<div class="absolute inset-0 z-1 pointer-events-none bg-linear-to-r from-black/80 via-black/50 to-black/20"></div>
	@endif

	<div class="__wrapper c-main relative z-10">
		<div class="__col grid grid-cols-1 lg:grid-cols-[1fr_auto] items-center gap-8 lg:gap-16">
			<div class="__content min-w-0">
				@if (!empty($g_action['header']))
				<h2 data-gsap-element="header" class="text-h3 m-header text-white">{{ $g_action['header'] }}</h2>
				@endif

				@if (!empty($g_action['text']))
				<div data-gsap-element="txt" class="__txt text-white/80">
					{!! $g_action['text'] !!}
				</div>
				@endif
			</div>

			@if (!empty($g_action['button1']['url']))
			<div class="inline-buttons m-btn justify-self-start lg:justify-self-end">
				<x-button
					:href="$g_action['button1']['url']"
					variant="primary"
					class="!text-secondary-900"
					data-gsap-element="btn">
					{{ $g_action['button1']['title'] }}
				</x-button>
			</div>
			@endif
		</div>
	</div>
</section>
