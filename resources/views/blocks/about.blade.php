<!--- about -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-about relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative">
		<div class="__col grid grid-cols-1 lg:grid-cols-3 items-center gap-8 lg:gap-10">

			<div class="__content ">
				<h3 data-gsap-element="header" class="text-primary">{{ $g_about['header'] }}</h3>

				<div data-gsap-element="txt" class="__txt mt-4">
					{!! $g_about['text'] !!}
				</div>

				<div class="inline-buttons m-btn">
					@if (!empty($g_about['button1']))
					<x-button
						:href="$g_about['button1']['url']"
						variant="primary"
						class=""
						data-gsap-element="btn">
						{{ $g_about['button1']['title'] }}
					</x-button>
					@endif

					@if (!empty($g_about['button2']))
					<x-button
						:href="$g_about['button2']['url']"
						variant="secondary"
						class=""
						data-gsap-element="btn">
						{{ $g_about['button2']['title'] }}
					</x-button>
					@endif
				</div>

			</div>

			@if (!empty($g_about['image']))
			<div data-gsap-element="img" class="__img h-full">
				<figure class="w-full h-full m-0">
					<picture class="w-full h-full">
						<img class="w-full h-full object-cover radius-img" src="{{ $g_about['image']['url'] }}" alt="{{ $g_about['image']['alt'] ?? '' }}">
					</picture>
				</figure>
			</div>
			@endif

			@if (!empty($r_about))

			<div class="grid gap-4">
				@foreach ($r_about as $item)
				<div data-gsap-element="card" class="__card relative bg-white radius flex gap-6 items-center p-8">

					<figure class="mb-0">
						<picture>
							<img class="" src="{{ get_template_directory_uri() }}/resources/images/check.svg" />
						</picture>
					</figure>
					@if (!empty($item['title']))
					<p class="text-h7">{{ $item['title'] }}</p>
					@endif
				</div>
				@endforeach
			</div>
			@endif

		</div>
	</div>

</section>