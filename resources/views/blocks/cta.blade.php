<!--- cta -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-cta relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper relative overflow-hidden">

		@if (!empty($g_octa['image']['url']))
		<figure class="absolute inset-0 m-0 z-0">
			<picture>
				<img src="{{ $g_octa['image']['url'] }}" alt="" class="w-full h-full object-cover object-right">
			</picture>
		</figure>
		@endif

		<div class="absolute top-0 left-0 bottom-0 z-10 w-full md:w-[75%]" style="border-radius: 0 0 9999px 0; background: linear-gradient(90deg, #2265CB 0%, #181D84 100%);"></div>

		<div class="__inside c-main grid grid-cols-1 md:grid-cols-2 items-center gap-6 relative z-20">
			<div class="__content w-full py-52">
				@if (!empty($g_octa['header']))
				<p data-gsap-element="header" class="block text-h3 text-white !m-header">{{ $g_octa['header'] }}</p>
				@endif
				@if (!empty($g_octa['txt']))
				<div data-gsap-element="txt" class="text-white">{!! $g_octa['txt'] !!}</div>
				@endif

				<div class="inline-buttons m-btn">
					@if (!empty($g_octa['button1']))
					<x-button
						:href="$g_octa['button1']['url']"
						variant="white"
						class=""
						data-gsap-element="btn">
						{{ $g_octa['button1']['title'] }}
					</x-button>
					@endif

					@if (!empty($g_octa['button2']))
					<x-button
						:href="$g_octa['button2']['url']"
						variant="secondary"
						class=""
						data-gsap-element="btn">
						{{ $g_octa['button2']['title'] }}
					</x-button>
					@endif
				</div>
			</div>

			@if ($form)
			<div data-gsap-element="form" class="bg-white radius p-10 -mt-20 md:-mt-0 mb-30 md:mb-0">
				<h4 class="!text-primary mb-4">{!! $g_octa['title'] !!}</h4>
				{!! do_shortcode($g_octa['shortcode']) !!}
			</div>
			@endif
		</div>

	</div>

</section>