@php
$blog_page_id = (int) get_option('page_for_posts');
$blog_url = $blog_page_id
	? get_permalink($blog_page_id)
	: (get_option('show_on_front') === 'posts' ? home_url('/') : (get_post_type_archive_link('post') ?: home_url('/')));
$categories = get_categories(['hide_empty' => true]);
$is_all = is_home() || is_post_type_archive('post');
@endphp

<section data-gsap-anim="section" class="b-blog relative">
	<div class="__wrapper c-main">
		@if (!empty($categories))
		<div class="__filters flex flex-wrap gap-4">
			<a @class(['__tab inline-flex items-center px-8 py-3 radius', 'is-active bg-primary' => $is_all, 'bg-white' => !$is_all]) href="{{ $blog_url }}">Wszystkie</a>
			@foreach ($categories as $category)
			<a @class(['__tab inline-flex items-center px-8 py-3 radius', 'is-active bg-primary' => is_category($category->term_id), 'bg-white' => !is_category($category->term_id)]) href="{{ get_category_link($category) }}">{{ $category->name }}</a>
			@endforeach
		</div>
		@endif

		@if (have_posts())
		<div class="__posts grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			@while (have_posts()) @php(the_post())
			@include('partials.content-post')
			@endwhile
		</div>
		{!! the_posts_pagination() !!}
		@else
		<div class="__empty">
			<p>Brak wpisów.</p>
		</div>
		@endif
	</div>
</section>
