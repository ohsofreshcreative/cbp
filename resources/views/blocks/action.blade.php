<!--- action -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-action relative isolate overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__hero relative flex items-center justify-center py-24 md:py-32">
		@if (!empty($g_action['image']['url']))
		<figure class="absolute inset-0 z-0 m-0 size-full">
			<img src="{{ $g_action['image']['url'] }}" alt="{{ $g_action['image']['alt'] ?? '' }}" class="size-full object-cover">
		</figure>
		<div class="absolute inset-0 z-1 pointer-events-none bg-black/50"></div>
		@endif

		<div class="__wrapper c-main relative z-10 text-center">
			@if (!empty($g_action['header']))
			<h2 data-gsap-element="header" class="text-h3 m-0 text-white [&_strong]:text-primary [&_b]:text-primary">
				{!! wp_kses($g_action['header'], ['strong' => [], 'b' => [], 'br' => [], 'em' => []]) !!}
			</h2>
			@endif
		</div>
	</div>

	<div class="__band relative bg-white">
		<svg class="__wave absolute bottom-full left-0 w-full" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
			<path d="M0 120C360 24 1080 24 1440 120V121H0V120Z" fill="currentColor" />
		</svg>

		<div class="__wrapper c-main relative">
			<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-16">
				@if (!empty($g_action['text']))
				<div data-gsap-element="txt" class="__txt order1 min-w-0">
					{!! $g_action['text'] !!}
				</div>
				@endif

				@if (!empty($g_action['button1']['url']))
				<div class="__offer-tile order2 justify-self-start lg:justify-self-end">
					<x-button
						:href="$g_action['button1']['url']"
						variant="white"
						class="__cta"
						data-gsap-element="btn">
						{{ $g_action['button1']['title'] }}
						<span class="__cta-icon" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
								<path d="M7 17 17 7m0 0H9m8 0v8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>
					</x-button>
				</div>
				@endif
			</div>
		</div>
	</div>
</section>
