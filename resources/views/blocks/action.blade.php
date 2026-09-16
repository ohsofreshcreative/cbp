<!--- action -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-action relative' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper">
		<div class="__card relative isolate overflow-hidden radius bg-primary px-6 py-8 md:px-14 md:py-12">
			@if (!empty($g_action['image']['url']))
			<figure class="__img absolute inset-y-0 right-0 z-0 m-0 hidden h-full w-2/5 md:block">
				<img src="{{ $g_action['image']['url'] }}" alt="{{ $g_action['image']['alt'] ?? '' }}" class="size-full object-cover" data-gsap-element="img">
			</figure>
			@endif

			<div class="__wash absolute inset-0 z-1 pointer-events-none hidden md:block" aria-hidden="true"></div>

			<div class="__content relative z-2 w-full md:w-1/2">
				@if (!empty($g_action['header']))
				<p data-gsap-element="header" class="__header m-0">{{ $g_action['header'] }}</p>
				@endif

				@if (!empty($g_action['text']))
				<div data-gsap-element="txt" class="__txt">
					{!! $g_action['text'] !!}
				</div>
				@endif

				@if (!empty($g_action['button1']['url']))
				<div class="inline-buttons m-btn">
					<x-button
						:href="$g_action['button1']['url']"
						variant="secondary"
						data-gsap-element="btn">
						{{ $g_action['button1']['title'] }}
					</x-button>
				</div>
				@endif
			</div>
		</div>
	</div>
</section>
