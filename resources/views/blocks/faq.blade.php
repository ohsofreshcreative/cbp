<!--- faq --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-faq relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_faq['header']))
		<h2 data-gsap-element="header" class="m-header text-center"><span class="text-primary">{{ $g_faq['header'] }}</span></h2>
		@endif

		@if (!empty($r_faq))
		<div data-gsap-element="tabs" class="tabs-wrapper flex flex-col">
			@foreach ($r_faq as $item)
			<div class="tabs border-b border-white/20">
				<input class="tab-check" type="checkbox" name="faq-{{ $faq_id }}" id="faq-{{ $faq_id }}-{{ $loop->index }}">
				<label class="tabs-label flex items-center justify-between" for="faq-{{ $faq_id }}-{{ $loop->index }}">
					@if (!empty($item['title']))
					<p>{{ $item['title'] }}</p>
					@endif
					<x-icon.arrow-up class="__arrow w-3 h-4" />
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
