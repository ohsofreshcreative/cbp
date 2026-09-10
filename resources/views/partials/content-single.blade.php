@php
$categories = get_the_category();
$category = !empty($categories) ? $categories[0] : null;
@endphp

<section data-gsap-anim="section" class="hero-blog bg-gradient relative overflow-visible">

	@if(has_post_thumbnail())
	<figure class="absolute inset-0 w-full h-full z-0 m-0">
		<picture class="w-full h-full">
			<img src="{{ get_the_post_thumbnail_url(get_the_ID(), 'large') }}" alt="{{ get_the_title() }}" class="w-full h-full object-cover" />
		</picture>
	</figure>
	<div class="absolute inset-0 z-1 pointer-events-none" style="background: linear-gradient(90deg, #171F87 0%, rgba(23, 31, 135, 0.60) 100%);"></div>
	@endif

	<div class="__wrapper c-main relative z-10 -spt">
		<div class="__content w-full pb-30">
			<div data-gsap-element="bread" class="__breadcrumb">
				@if (function_exists('woocommerce_breadcrumb'))
				{!! woocommerce_breadcrumb() !!}
				@endif
			</div>

			<div class="__top mt-20">
				@if ($category)
				<a data-gsap-element="header" href="{{ get_category_link($category->term_id) }}" class="bg-secondary-lighter hover:bg-secondary-light border border-primary-light rounded-full text-sm px-4 py-3">{{ $category->name }}</a>
				@endif
				<h1 data-gsap-element="header" class="text-h2 text-white mt-6">{{ get_the_title() }}</h1>
				@if(has_excerpt())
				<div data-gsap-element="content" class="text-white mt-4">
					{!! get_the_excerpt() !!}
				</div>
				@endif
			</div>
		</div>
		<a class="absolute bg-secondary hover:bg-secondary-hover w-20 h-20 rounded-full flex items-center justify-center mx-auto bottom-0 translate-y-1/2 z-20" href="#tresc"><img src="{{ get_template_directory_uri() }}/resources/images/anchor-arrow.svg" /></a>
	</div>
</section>

@php
$content = apply_filters('the_content', get_the_content());

preg_match_all('/<h([1-4])[^>]*>(.*?)<\/h[1-4]>/', $content, $matches, PREG_SET_ORDER);

		$toc = '<nav class="toc">
			<ul>';
				$used_ids = [];
				foreach ($matches as $match) {
				$level = $match[1];
				$title = strip_tags($match[2]);
				$id = sanitize_title($title);
				$base_id = $id;
				$i = 2;
				while (in_array($id, $used_ids)) {
				$id = $base_id . '-' . $i;
				$i++;
				}
				$used_ids[] = $id;
				$content = preg_replace(
				'/<h' . $level . '[^>]*>' . preg_quote($match[2], '/' ) . '<\/h' . $level . '>/' , '<h' . $level . ' id="' . $id . '">' . $match[2] . '</h' . $level . '>' ,
					$content,
					1
					);
					$toc .='<li class="toc-h' . $level . '"><a href="#' . $id . '">' . $title . '</a></li>' ;
					}
					$toc .='</ul></nav>' ;
					@endphp

					<div id="tresc" class="__content c-main __entry -smt grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-10">

					<div class="relative md:sticky top-0 md:top-30 h-max">
						<p class="text-h5 m-title">Spis treści</p>
						@if(count($matches))
						{!! $toc !!}
						@endif
					</div>

					<div id="tresc" class="__entry">
						{!! $content !!}
					</div>

					</div>

					@php
					$current_id = get_the_ID();
					$categories = wp_get_post_categories($current_id);
					$related_args = [
					'category__in' => $categories,
					'post__not_in' => [$current_id],
					'posts_per_page' => 3,
					'ignore_sticky_posts' => 1,
					];
					$related_query = new WP_Query($related_args);
					@endphp

					@if($related_query->have_posts())
					<section class="related-posts c-main border-t border-dashed border-secondary-light -smt pt-20 pb-26">
						<h3 class="text-2xl text-primary mb-6">Zobacz również</h3>
						<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
							@while($related_query->have_posts())
							@php($related_query->the_post())
							<article @php(post_class(''))>
								<a class="rounded-2xl group" href="{{ get_permalink() }}">
									<div class="__content relative bg-white rounded-4xl p-6">
										@if (has_post_thumbnail())
										<div class="block rounded-2xl overflow-hidden">
											<img src="{{ get_the_post_thumbnail_url(null, 'large') }}" alt="{{ get_the_title() }}" class="w-full img-s object-cover">
										</div>
										@endif
										<h6 class="mt-6">
											{!! get_the_title() !!}
										</h6>
										<!--  <div class="mt-2">
            @php(the_excerpt())
        </div> -->
										<p href="{{ get_permalink() }}" class="btn btn-outline-secondary group-hover:!bg-secondary group-hover:!text-white !px-6 !py-3 mt-4">
											Przeczytaj
										</p>
									</div>
								</a>

							</article>
							@endwhile
							@php(wp_reset_postdata())
						</div>
					</section>
					@endif


					<script>
						document.addEventListener('DOMContentLoaded', function() {
							const headings = document.querySelectorAll('h1[id], h2[id], h3[id], h4[id]'); // Select all headings with IDs
							const tocLinks = document.querySelectorAll('.toc ul li a'); // Select all links in the TOC

							function updateActiveLink() {
								headings.forEach((heading) => {
									const headingTop = heading.getBoundingClientRect().top;
									const windowHeight = window.innerHeight;

									if (headingTop < windowHeight - 300) {
										// Remove the 'active' class from all TOC links
										tocLinks.forEach((link) => {
											link.parentNode.classList.remove('active');
										});

										// Add the 'active' class to the corresponding TOC link
										const id = heading.id;
										const activeLink = document.querySelector(`.toc ul li a[href="#${id}"]`);
										if (activeLink) {
											activeLink.parentNode.classList.add('active');
										}
									}
								});
							}
							updateActiveLink();

							window.addEventListener('scroll', updateActiveLink);
						});
					</script>