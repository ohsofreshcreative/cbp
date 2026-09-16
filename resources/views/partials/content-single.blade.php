@php
$categories = get_the_category();
$category = !empty($categories) ? $categories[0] : null;
$content = apply_filters('the_content', get_the_content());
preg_match_all('/<h([1-4])[^>]*>(.*?)<\/h[1-4]>/', $content, $matches, PREG_SET_ORDER);

$toc = '';
$used_ids = [];

if ($matches) {
	$toc .= '<nav class="toc"><ul>';
	foreach ($matches as $match) {
		$level = $match[1];
		$title = strip_tags($match[2]);
		$id = sanitize_title($title);
		$base_id = $id;
		$i = 2;
		while (in_array($id, $used_ids, true)) {
			$id = $base_id . '-' . $i;
			$i++;
		}
		$used_ids[] = $id;
		$content = preg_replace(
			'/<h' . $level . '[^>]*>' . preg_quote($match[2], '/') . '<\/h' . $level . '>/',
			'<h' . $level . ' id="' . $id . '">' . $match[2] . '</h' . $level . '>',
			$content,
			1
		);
		$toc .= '<li class="toc-h' . $level . '"><a href="#' . $id . '">' . $title . '</a></li>';
	}
	$toc .= '</ul></nav>';
}
@endphp

<section data-gsap-anim="section" class="b-blog-hero relative -menu-pt">
	<div class="__wrapper c-main">
		<div class="__breadcrumb" data-gsap-element="header">
			@if (function_exists('yoast_breadcrumb'))
			{!! yoast_breadcrumb('<p id="breadcrumbs">', '</p>') !!}
			@else
			<p>
				<a href="{{ home_url('/') }}">Strona główna</a>
				@php $blog_page_id = (int) get_option('page_for_posts'); @endphp
				@if ($blog_page_id)
				<a href="{{ get_permalink($blog_page_id) }}">Blog</a>
				@endif
				@if ($category)
				<span>{{ $category->name }}</span>
				@endif
			</p>
			@endif
		</div>

		<h1 data-gsap-element="header" class="text-h2 text-white">{{ get_the_title() }}</h1>

		<div class="__meta flex flex-wrap gap-6">
			<p>Autor: <span>{{ get_the_author() }}</span></p>
			<p>Opublikowano: <span>{{ get_the_date() }}</span></p>
		</div>
	</div>
</section>

<section data-gsap-anim="section" class="b-blog-single relative">
	<div class="__wrapper c-main flex flex-col gap-8">
		@if (has_post_thumbnail())
		<figure class="__hero-img radius overflow-hidden m-0">
			<img src="{{ get_the_post_thumbnail_url(get_the_ID(), 'large') }}" alt="{{ get_the_title() }}" class="w-full object-cover" data-gsap-element="img" />
		</figure>
		@endif

		<div id="tresc" @class(['__layout grid grid-cols-1 gap-8', 'md:grid-cols-[1fr_2fr]' => $matches])>
			@if ($matches)
			<div class="__toc relative md:sticky h-max">
				<p class="text-h5">Spis treści</p>
				{!! $toc !!}
			</div>
			@endif

			<div class="__entry">
				{!! $content !!}
			</div>
		</div>
	</div>
</section>

@php
$current_id = get_the_ID();
$related_args = [
	'category__in' => wp_get_post_categories($current_id) ?: [0],
	'post__not_in' => [$current_id],
	'posts_per_page' => 3,
	'ignore_sticky_posts' => 1,
];
$related_query = new WP_Query($related_args);
@endphp

@if ($related_query->have_posts())
<section data-gsap-anim="section" class="b-blog b-blog-related relative section-white -smt">
	<div class="__wrapper c-main">
		<h2 data-gsap-element="header" class="text-h2 m-header">Podobne artykuły</h2>
		<div class="__posts grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			@while ($related_query->have_posts())
			@php($related_query->the_post())
			@include('partials.content-post')
			@endwhile
			@php(wp_reset_postdata())
		</div>
	</div>
</section>
@endif

@include('partials.cta')
