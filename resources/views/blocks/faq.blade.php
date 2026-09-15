<!--- faq --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-faq relative -smt isolate overflow-clip' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__top flex flex-col items-center text-center">
			<p class="__label font-header text-primary mt-0 mb-3">{{ !empty($g_faq['label']) ? $g_faq['label'] : 'FAQ' }}</p>
			@if (!empty($g_faq['header']))
			<h2 data-gsap-element="header" class="text-h3 m-header text-white">{{ $g_faq['header'] }}</h2>
			@endif
		</div>

		@if (!empty($r_faq))
		<div data-gsap-element="tabs" class="tabs-wrapper flex flex-col c-narrow mx-auto">
			@foreach ($r_faq as $item)
			<div class="tabs">
				<input class="tab-check" type="checkbox" id="faq-{{ $faq_id }}-{{ $loop->index }}">
				<label class="tabs-label flex items-center justify-between gap-4" for="faq-{{ $faq_id }}-{{ $loop->index }}">
					@if (!empty($item['title']))
					<p class="font-header mt-0 mb-0 text-white">{{ $item['title'] }}</p>
					@endif
					<svg class="__arrow shrink-0 text-white" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
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
