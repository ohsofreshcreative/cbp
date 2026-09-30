@php
$blog_page_id = (int) get_option('page_for_posts');
$hero_header = $blog_page_id ? get_the_title($blog_page_id) : '';
$hero_text = $blog_page_id ? get_post_field('post_excerpt', $blog_page_id) : '';
@endphp

<section data-gsap-anim="section" class="b-blog-hero relative -menu-pt">
	<div class="__wrapper c-main">
		<div class="__breadcrumb" data-gsap-element="header">
			@if (function_exists('rank_math_the_breadcrumbs'))
			@php
				rank_math_the_breadcrumbs([
					'wrap_before' => '<nav aria-label="Okruszki" class="rank-math-breadcrumb"><p id="breadcrumbs">',
					'wrap_after' => '</p></nav>',
				]);
			@endphp
			@else
			<p>
				<a href="{{ home_url('/') }}">Strona główna</a>
				<span>Blog</span>
			</p>
			@endif
		</div>

		<div class="__inside grid grid-cols-1 md:grid-cols-2 items-end gap-8">
			<h1 data-gsap-element="header" class="text-h3 text-white [&_strong]:text-primary">
				@if (!empty($hero_header) && $hero_header !== 'Blog')
				{{ $hero_header }}
				@else
				Wiedza, która pomaga podejmować <strong>świadome</strong> decyzje
				@endif
			</h1>
			@if (!empty($hero_text))
			<div data-gsap-element="text" class="text-white">{!! wp_kses_post($hero_text) !!}</div>
			@else
			<p data-gsap-element="text" class="text-white block pb-2">Poznaj odpowiedzi na najczęstsze pytania dotyczące badań poligraficznych, ich przebiegu, zastosowania oraz możliwości i ograniczeń.</p>
			@endif
		</div>
	</div>
</section>
