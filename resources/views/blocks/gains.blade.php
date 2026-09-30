<!--- gains -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-gains relative bg-[#fafafa]! -smt isolate' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if (!empty($g_gains['image']['url']))
	<img class="__deco absolute left-0 pointer-events-none object-contain object-bottom-left bottom-0 opacity-50" src="{{ $g_gains['image']['url'] }}" alt="" data-gsap-element="img">
	@endif

	<div class="__wrapper c-main relative">
		<div class="__col grid grid-cols-1 md:grid-cols-2 items-start gap-8">
			<div class="__content relative md:sticky top-0 md:top-20 h-max">
				@if (!empty($g_gains['header']))
				<h2 data-gsap-element="header" class="m-header">{{ $g_gains['header'] }}</h2>
				@endif
				@if (!empty($g_gains['text']))
				<div data-gsap-element="txt" class="__txt">{!! $g_gains['text'] !!}</div>
				@endif
			</div>

			@if (!empty($r_gains))
			<div class="__cards grid gap-8">
				@foreach ($r_gains as $item)
				<article data-gsap-element="card" class="__card bg-white overflow-hidden radius border border-dashed border-primary p-8">
					@if (!empty($item['header']))
					<p class="__title text-h6">{{ $item['header'] }}</p>
					@endif
					@if (!empty($item['text']))
					<div class="__txt">{!! $item['text'] !!}</div>
					@endif
				</article>
				@endforeach
			</div>
			@endif
		</div>
	</div>
</section>
