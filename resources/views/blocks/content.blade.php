<!--- content -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-content relative -smt overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<!-- <div class="__glow pointer-events-none absolute right-[-200px] top-[-100px] -z-10 size-[700px]" aria-hidden="true">
		<div class="absolute -inset-[31.43%]">
			<img class="block size-full max-w-none" src="{{ $theme_uri }}/resources/images/contact-glow-soft.svg" alt="" width="1140" height="1140" />
		</div>
	</div> -->

	<div class="__wrapper c-main relative">

		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20">
			@if (!empty($g_content['image']))
			<figure data-gsap-element="img" class="__img h-full order1">
				<picture>
					<img class="radius-img h-[504px] max-h-[504px] w-full object-cover" src="{{ $g_content['image']['url'] }}" alt="{{ $g_content['image']['alt'] ?? '' }}">
				</picture>
			</figure>
			@endif

			<div class="__content order2">
				@if (!empty($g_content['title']))
				<p data-gsap-element="title" class="__label flex items-center gap-2 text-secondary-700!">
					<x-icon.ekg class="w-8 h-7 shrink-0 text-primary-800" />
					<span>{{ $g_content['title'] }}</span>
				</p>
				@endif
				<h2 data-gsap-element="header" class="__header text-h4 m-header">{{ $g_content['header'] }}</h2>

				<div data-gsap-element="txt" class="__txt">
					{!! $g_content['text'] !!}
				</div>

				@if (!empty($g_content['button1']) || !empty($g_content['button2']))
				<div class="inline-buttons m-btn">
					@if (!empty($g_content['button1']))
					<x-button
						:href="$g_content['button1']['url']"
						variant="primary"
						class=""
						data-gsap-element="btn">
						{{ $g_content['button1']['title'] }}
					</x-button>
					@endif

					@if (!empty($g_content['button2']))
					<x-button
						:href="$g_content['button2']['url']"
						variant="secondary"
						class=""
						data-gsap-element="btn">
						{{ $g_content['button2']['title'] }}
					</x-button>
					@endif
				</div>
				@endif

			</div>

		</div>
	</div>

</section>