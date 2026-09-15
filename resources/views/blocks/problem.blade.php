<!--- problem -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-problem relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_problem['header']))
		<h2 data-gsap-element="header" class="m-header text-center">{{ $g_problem['header'] }}</h2>
		@endif

		@if (!empty($r_problem))
		<div class="__list grid gap-8 lg:gap-20">
			@foreach ($r_problem as $item)
			<article data-gsap-element="card" class="__row grid grid-cols-1 md:grid-cols-2 items-center gap-8 lg:gap-20">
				@if (!empty($item['image']['url']))
				<figure data-gsap-element="img" @class(['__img m-0 overflow-hidden radius-img', 'md:order-2' => $loop->even])>
					<picture>
						<img class="w-full object-cover" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}">
					</picture>
				</figure>
				@endif

				<div @class(['__content', 'md:order-1' => $loop->even])>
					@if (!empty($item['header']))
					<h3 data-gsap-element="header">{{ $item['header'] }}</h3>
					@endif
					@if (!empty($item['text']))
					<div data-gsap-element="txt" class="__txt">
						{!! $item['text'] !!}
					</div>
					@endif
				</div>
			</article>
			@endforeach
		</div>
		@endif
	</div>
</section>
