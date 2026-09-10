<!--- contact --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-contact  relative' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if (!empty($g_contact_1['image']))
	<figure class="absolute inset-0 w-full h-full z-0 m-0">
		<picture class="w-full h-full">
			<img src="{{ $g_contact_1['image']['url'] }}" alt="{{ $g_contact_1['image']['alt'] }}" class="w-full h-full object-cover" />
		</picture>
	</figure>
	@endif

	@if (!empty($g_contact_1['image']))
	<div class="absolute inset-0 z-1 pointer-events-none" style="background: linear-gradient(90deg, #171F87 5.84%, rgba(23, 31, 135, 0.20) 100.47%);"></div>
	@endif

	<img class="absolute -bottom-[122px] left-1/3 -translate-x-1/2 z-10 w-auto max-w-none" src="{{ get_template_directory_uri() }}/resources/images/contact-shape.svg" />

	<div class="__wrapper c-main relative z-2 pt-10 pb-10 md:pt-48 md:pb-32">

		<div class="relative grid grid-cols-1 lg:grid-cols-2 items-center gap-10 z-10">
			<div class="__content flex flex-col justify-between">
				<h2 data-gsap-element="header" class="text-white m-header">{!! $g_contact_1['header'] !!}</h2>
				<p data-gsap-element="txt" class="text-white">{!! $g_contact_1['address'] !!}</p>
				<a data-gsap-element="txt" class="__phone flex items-center !text-white hover:!text-primary-200 w-max mt-6" href="tel:{{ $g_contact_1['phone'] }}">{{ $g_contact_1['phone'] }}</a>
				<a data-gsap-element="txt" class="__mail flex items-center !text-white hover:!text-primary-200 w-max mt-2" href="mailto:{{ $g_contact_1['mail'] }}">{{ $g_contact_1['mail'] }}</a>
				<x-button
					href="#lokalizacje"
					variant="secondary"
					class="mt-6"
					data-gsap-element="btn">
					Sprawdź lokalizacje
				</x-button>
			</div>

			<div data-gsap-element="form" class="bg-white radius p-10">
				<h4 class="!text-primary mb-4">{!! $g_contact_2['title'] !!}</h4>
				{!! do_shortcode($g_contact_2['shortcode']) !!}
			</div>
		</div>
	</div>

</section>