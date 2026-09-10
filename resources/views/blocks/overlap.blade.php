<!--- overlap --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-overlap relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative z-10">
		<div class="__content order2">
			<div class="__txt w-full md:w-1/2 mx-auto">
				<h2 data-gsap-element="header" class="text-center m-header">{{ $g_overlap['header'] }}</h2>

				<div data-gsap-element="header" class="text-center">
					{!! $g_overlap['text'] !!}
				</div>
			</div>

			<div class="grid grid-cols-1 gap-8 mt-14">
				@foreach ($r_overlap as $item)
				<div class="gsap__cards __cards sticky top-20 mt-4">
					<div data-gsap-element="card" class="gsap__card __card p-8 rounded-4xl" style="background-image:url({{ $item['image']['url'] }}); background-size: cover; background-position: center;">
						<div class="__box bg-white rounded-3xl w-full md:w-1/2 p-6 md:p-10 mt-80 mb-0 md:mb-10 mx-0 md:mx-20">
							<p class="text-h3">{{ $item['header'] }}</p>
							<div class="">{!! $item['text'] !!}</div>
							@if (!empty($item['button']))
							<x-button
								:href="$item['button']['url']"
								variant="secondary-small"
								class="mt-6">
								{{ $item['button']['title'] }}
							</x-button>
							@endif
						</div>
					</div>
				</div>
				@endforeach
			</div>

		</div>
	</div>
</section>