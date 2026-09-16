<!--- explore -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-explore relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__top">
			@if (!empty($g_explore['label']))
			<p data-gsap-element="header" class="__label">{{ $g_explore['label'] }}</p>
			@endif
			@if (!empty($g_explore['header']))
			<h2 data-gsap-element="header" class="m-header">{{ $g_explore['header'] }}</h2>
			@endif
		</div>

		<div class="__col grid grid-cols-1 md:grid-cols-[minmax(0,296px)_1fr] gap-8">
			@if (!empty($g_explore['image']['url']))
			<figure class="__img relative m-0 overflow-hidden radius">
				<img src="{{ $g_explore['image']['url'] }}" alt="{{ $g_explore['image']['alt'] ?? '' }}" class="size-full object-cover" data-gsap-element="img">
				@if (!empty($g_explore['caption']))
				<figcaption class="__caption absolute bottom-4 left-4">{{ $g_explore['caption'] }}</figcaption>
				@endif
			</figure>
			@endif

			@if (!empty($r_explore))
			<div class="__cards grid grid-cols-1 md:grid-cols-2 gap-8">
				@foreach ($r_explore as $item)
				<article data-gsap-element="card" class="__card relative flex items-center overflow-hidden radius bg-secondary-800 p-6">
					@if (!empty($item['image']['url']))
					<img src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" class="__logo object-contain">
					@endif
					<div class="__inside">
						@if (!empty($item['header']))
						<p class="__title">{{ $item['header'] }}</p>
						@endif
						@if (!empty($item['text']))
						<div class="__txt">{!! $item['text'] !!}</div>
						@endif
					</div>
				</article>
				@endforeach
			</div>
			@endif
		</div>
	</div>
</section>
