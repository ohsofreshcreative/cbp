<!-- banner --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-banner relative -spt overflow-visible' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if (!empty($g_banner['image']))
	<figure class="absolute inset-0 w-full h-full z-0 m-0">
		<picture class="w-full h-full">
			<img src="{{ $g_banner['image']['url'] }}" alt="{{ $g_banner['image']['alt'] }}" class="w-full h-full object-cover" />
		</picture>
	</figure>
	@endif

	@if (!empty($g_banner['video']) || !empty($g_banner['image']))
	<div class="absolute inset-0 z-1 pointer-events-none" style="background: linear-gradient(90deg, #171F87 5.84%, rgba(23, 31, 135, 0.20) 100.47%);"></div>
	@endif

	@if (!empty($g_banner['shape']))
	<img class="absolute -bottom-[109px] w-[2109px] max-w-none left-1/2 -translate-x-1/2" src="{{ get_template_directory_uri() }}/resources/images/banner-shape.svg" />
	@else
	<img class="absolute -bottom-[109px] w-[2109px] max-w-none left-1/2 -translate-x-1/2" src="{{ get_template_directory_uri() }}/resources/images/banner-shape2.svg" />
	@endif

	<div class=" __wrapper c-main relative z-10">
		<div class="__content relative flex flex-col justify-center w-full md:w-10/12 lg:w-8/12 z-20 pt-48 pb-62">
			<h1 data-gsap-element="header" class="text-h2 text-white">
				{{ $g_banner['title'] }}
			</h1>
			@if (!empty($g_banner['text']))
            <div data-gsap-element="text" class="text-white mt-4">
                {!! $g_banner['text'] !!}
            </div>
			@endif

			<div class="inline-buttons m-btn">
				@if (!empty($g_banner['button1']))
				<x-button
					:href="$g_banner['button1']['url']"
					variant="secondary"
					class=""
					data-gsap-element="btn">
					{{ $g_banner['button1']['title'] }}
				</x-button>
				@endif

				@if (!empty($g_banner['button2']))
				<x-button
					:href="$g_banner['button2']['url']"
					variant="white"
					class=""
					data-gsap-element="btn">
					{{ $g_banner['button2']['title'] }}
				</x-button>
				@endif
			</div>
		</div>
	</div>

</section>