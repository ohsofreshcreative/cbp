<!--- faq --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-faq relative -smt isolate overflow-clip py-14 md:py-24' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__top flex flex-col items-center text-center">
			<svg class="text-primary mb-5" width="36" height="28" viewBox="0 0 30 26" fill="none" aria-hidden="true">
				<path d="M1 14h4L8 4l4 19 4-17 4 14 3-12 3 6h3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
			</svg>
			@if (!empty($g_faq['header']))
			<h2 data-gsap-element="header" class="text-h3 m-header text-primary">{{ $g_faq['header'] }}</h2>
			@endif
		</div>

		@if (!empty($r_faq))
		<div data-gsap-element="tabs" class="tabs-wrapper flex flex-col c-narrow mx-auto">
			@foreach ($r_faq as $item)
			<div class="tabs">
				<input class="tab-check" type="checkbox" id="faq-{{ $faq_id }}-{{ $loop->index }}">
				<label class="tabs-label flex items-center justify-between gap-4" for="faq-{{ $faq_id }}-{{ $loop->index }}">
					@if (!empty($item['title']))
					<p class="font-header text-h6 text-white mt-0 mb-0">{{ $item['title'] }}</p>
					@endif
					<x-icon.arrow-up class="__arrow text-primary w-3 h-4 shrink-0" />
				</label>
				@if (!empty($item['txt']))
				<div class="tabs-content">
					{!! $item['txt'] !!}
				</div>
				@endif
			</div>
			@endforeach
		</div>
		@endif
	</div>
</section>
