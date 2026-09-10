<!--- faq --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-faq relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-20">

		<div class="__content">
			<h3 data-gsap-element="header" class="">{{ $g_faq['header'] }}</h3>
			@if (!empty($g_faq['image']))
			<div data-gsap-element="img" class="__img order1 mt-10">
				<img class="__img object-cover" src="{{ $g_faq['image']['url'] }}" alt="{{ $g_faq['image']['alt'] ?? '' }}">
			</div>
			@endif
		</div>
		<div data-gsap-element="tabs" class="tabs-wrapper flex flex-col mt-4">
			@foreach ($r_faq as $item)
			<div class="tabs rounded-2xl bg-white border border-secondary h-max">
				<input class="tab-check" type="checkbox" name="radio-a" id="check{{ $loop->index }}">
				<label class="tabs-label flex items-center justify-between" for="check{{ $loop->index }}">
					<div class="flex items-center gap-4">
						<p class="!text-lg font-header">{{ $item['title'] }}</p>
					</div>
					<x-icon.arrow-up class="__arrow text-secondary w-3 h-4" />
				</label>
				<div class="tabs-content">
					{!! $item['txt'] !!}
				</div>
			</div>
			@endforeach
		</div>

	</div>

</section>