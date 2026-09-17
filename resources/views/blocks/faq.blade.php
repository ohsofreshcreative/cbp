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
		<div class="__top flex flex-col items-center text-center">
			<p class="__label text-primary">{{ !empty($g_faq['label']) ? $g_faq['label'] : 'FAQ' }}</p>
			@if (!empty($g_faq['header']))
			<h2 data-gsap-element="header" class="m-header">{{ $g_faq['header'] }}</h2>
			@endif
		</div>

		@if (!empty($r_faq))
		<div class="__list flex flex-col">
			@foreach ($r_faq as $item)
			<details data-gsap-element="card" class="__item radius bg-secondary-800">
				<summary class="__summary">
					@if (!empty($item['title']))
					<p>{{ $item['title'] }}</p>
					@endif
					<svg class="__arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</summary>
				<div class="__content">
					@if (!empty($item['txt']))
					{!! $item['txt'] !!}
					@endif
				</div>
			</details>
			@endforeach
		</div>
		@endif
	</div>
</section>
