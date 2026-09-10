@extends('layouts.app')

@section('content')

@php
$term = get_queried_object();
$categories = get_categories();

$category_header = get_field('category_header', $term);
$category_description = get_field('category_description', $term);
$category_image = get_field('category_image', $term);

$cta = get_field('g_octa', 'option');
$form = !empty($cta['shortcode']);

// Pobranie pól ACF dla sekcji 'bottom'
$section_id = $bottom['section_id'] ?? '';
$section_class = $bottom['section_class'] ?? '';
$flip = $bottom['flip'] ?? false;

// Przygotowanie klas CSS
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';

// Wygenerowanie unikalnego ID dla SVG
$unique_id = 'clip_'.uniqid();
@endphp

<div class="hero category-header relative">
	@if(!empty($category_image['url']))
	<figure class="absolute inset-0 m-0 z-0">
		<picture>
			<img src="{{ $category_image['url'] }}" alt="" class="w-full h-full object-cover object-center">
		</picture>
	</figure>
	@endif
	<div class="absolute inset-0 bg-primary @if(!empty($category_image['url'])) opacity-80 @endif"></div>
	<div data-gsap-element="bread" class="__breadcrumb mb-4">
		@if (function_exists('yoast_breadcrumb'))
		{!! yoast_breadcrumb('<p id="breadcrumbs">','</p>') !!}
		@endif
	</div>
	<div class="__wrapper c-main relative z-10 pt-60 pb-26">
		<div class="__content w-full md:w-2/3">
			<h2 class="text-white m-header">
				{!! $category_header ?: get_the_archive_title() !!}
			</h2>
			@if ($category_description)
			<div class="text-white text-xl">
				{!! $category_description !!}
			</div>
			@endif
		</div>
	</div>
</div>

</div>



@if (have_posts())
<div class="__posts c-main !mt-10 posts grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
	@while (have_posts()) @php(the_post())

	@includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
	@endwhile
</div>

{{-- {!! get_the_posts_navigation() !!} --}}
{!! the_posts_pagination() !!}
@else
<div class="mt-20 mb-20">
	<div class="c-main">
		<h3 class="">Brak wpisów w tej kategorii.</h3>
		<a class="main-btn m-btn" href="/wszystkie-wpisy/">Sprawdź wszystkie wpisy</a>
	</div>
</div>
@endif

<!-- bottom-block -->

<section class="b-cta relative -smt">

	<div class="__wrapper relative overflow-hidden">

		@if (!empty($cta['image']['url']))
		<figure class="absolute inset-0 m-0 z-0">
			<picture>
				<img src="{{ $cta['image']['url'] }}" alt="" class="w-full h-full object-cover object-right">
			</picture>
		</figure>
		@endif

		<div class="absolute top-0 left-0 bottom-0 z-10 w-full md:w-[75%]" style="border-radius: 0 0 9999px 0; background: linear-gradient(90deg, #2265CB 0%, #181D84 100%);"></div>

		<div class="__inside c-main grid grid-cols-1 md:grid-cols-2 items-center gap-6 relative z-20">
			<div class="__content w-full py-52">
				@if (!empty($cta['header']))
				<p data-gsap-element="header" class="block text-h3 text-white !m-header">{{ $cta['header'] }}</p>
				@endif
				@if (!empty($cta['txt']))
				<div data-gsap-element="txt" class="text-white">{!! $cta['txt'] !!}</div>
				@endif

				<div class="inline-buttons m-btn">
					@if (!empty($cta['button1']))
					<x-button
						:href="$cta['button1']['url']"
						variant="white"
						class=""
						data-gsap-element="btn">
						{{ $cta['button1']['title'] }}
					</x-button>
					@endif

					@if (!empty($cta['button2']))
					<x-button
						:href="$cta['button2']['url']"
						variant="secondary"
						class=""
						data-gsap-element="btn">
						{{ $cta['button2']['title'] }}
					</x-button>
					@endif
				</div>
			</div>

			<!-- 	@if ($form)
			<div data-gsap-element="form" class="bg-white radius p-10 -mt-20 md:-mt-0 mb-30 md:mb-0">
				<h4 class="!text-primary mb-4">{!! $cta['title'] !!}</h4>
				{!! do_shortcode($cta['shortcode']) !!}
			</div>
			@endif -->
		</div>

	</div>

</section>

@endsection