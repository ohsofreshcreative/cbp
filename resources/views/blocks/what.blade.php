<!--- what -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-what relative -smt isolate overflow-clip text-white' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-10 lg:gap-20">
			<div class="__content order1 min-w-0">
				@if (!empty($g_what['header']))
				<h2 data-gsap-element="header" class="text-h3 m-header text-white">{{ $g_what['header'] }}</h2>
				@endif

				@if (!empty($r_what))
				<div class="__list">
					@foreach ($r_what as $item)
					<div data-gsap-element="card" class="__card">
						@if (!empty($item['header']))
						<p class="font-header !text-primary mt-0 mb-1">{{ $item['header'] }}</p>
						@endif
						@if (!empty($item['text']))
						<div data-gsap-element="txt" class="__txt">
							{!! $item['text'] !!}
						</div>
						@endif
					</div>
					@endforeach
				</div>
				@endif
			</div>

			@if (!empty($g_what['image']['url']))
			<div class="__media order2 relative justify-self-center lg:justify-self-end">
				<figure data-gsap-element="img" class="__img overflow-hidden rounded-full aspect-square img-2xl m-0">
					<img class="size-full object-cover" src="{{ $g_what['image']['url'] }}" alt="{{ $g_what['image']['alt'] ?? '' }}">
				</figure>
				<x-icon.ekg class="__ekg absolute text-primary pointer-events-none" />
			</div>
			@endif
		</div>
	</div>
</section>
