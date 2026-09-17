<!--- wehelp -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-wehelp relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative">
		@if (!empty($g_wehelp['header']))
		<h2 data-gsap-element="header" class="m-header">{{ $g_wehelp['header'] }}</h2>
		@endif

		<div class="__col flex flex-col lg:flex-row lg:items-center lg:justify-between">
			@if (!empty($r_wehelp))
			<div class="__list">
				@foreach ($r_wehelp as $item)
				<div data-gsap-element="card" class="__point">
					<div class="__txt">
						@if (!empty($item['header']))
						<p class="text-primary">{{ $item['header'] }}</p>
						@endif
						@if (!empty($item['text']))
						{!! $item['text'] !!}
						@endif
					</div>
				</div>
				@endforeach
			</div>
			@endif

			@if (!empty($g_wehelp['image']['url']))
			<div class="__media relative">
				<figure data-gsap-element="img" class="__img m-0">
					<img src="{{ $g_wehelp['image']['url'] }}" alt="{{ $g_wehelp['image']['alt'] ?? '' }}" width="512" height="512">
				</figure>
				<img class="__ekg pointer-events-none" src="{{ get_template_directory_uri() }}/resources/images/wehelp-ekg.svg" alt="" width="409" height="324">
			</div>
			@endif
		</div>
	</div>
</section>
