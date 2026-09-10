<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-logos relative -smt overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if(!empty($g_logos['header']))
	<div class="__wrapper c-main relative">
		<h4 data-gsap-element="header" class="w-full text-primary md:w-1/2 mb-8">{{ $g_logos['header'] }}</h4>
	</div>
	@endif

	@if (!empty($g_logos['gallery']))
	<div class="relative w-full overflow-hidden my-6">

		<div class="flex w-max items-center animate-infinite-scroll py-2">

			@for ($copy = 0; $copy < 4; $copy++)
				@foreach ($g_logos['gallery'] as $image)
				<div class="bg-white flex items-center justify-center p-4 rounded-[12px] shadow-sm w-48 h-24 shrink-0 mr-8" @if($copy> 0) aria-hidden="true" @endif>
				<img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? '' }}" class="max-h-12 w-auto max-w-[80%] object-contain transition-all duration-300">
		</div>
		@endforeach
		@endfor
	</div>
	</div>
	@endif
</section>