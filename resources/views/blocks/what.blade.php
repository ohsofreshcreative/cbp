<!--- what -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-what relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20">
			<div class="__content order2">
				@if (!empty($g_what['header']))
				<h2 data-gsap-element="header" class="m-header">{{ $g_what['header'] }}</h2>
				@endif

				@if (!empty($r_what))
				<div class="__list flex flex-col gap-8">
					@foreach ($r_what as $item)
					<div data-gsap-element="card" class="__card">
						@if (!empty($item['header']))
						<p><span class="text-primary">{{ $item['header'] }}</span></p>
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

			@if (!empty($g_what['image']))
			<figure data-gsap-element="img" class="__img order1 overflow-hidden rounded-full aspect-square">
				<img class="w-full h-full object-cover" src="{{ $g_what['image']['url'] }}" alt="{{ $g_what['image']['alt'] ?? '' }}">
			</figure>
			@endif
		</div>
	</div>
</section>
