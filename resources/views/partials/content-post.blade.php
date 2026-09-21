<article @php(post_class('__card flex flex-col'))>
	<a class="flex flex-col gap-8 group" href="{{ get_permalink() }}">
		@if (has_post_thumbnail())
		<figure class="__img radius overflow-hidden m-0">
			<img src="{{ get_the_post_thumbnail_url(null, 'large') }}" alt="{{ get_the_title() }}" class="w-full object-cover" data-gsap-element="img" />
		</figure>
		@endif
		@if (get_the_title())
		<h3 data-gsap-element="header" class="__title">{{ get_the_title() }}</h3>
		@endif
		<span class="__arrow" aria-hidden="true">
			<svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="0.5" y="0.5" width="55" height="55" rx="27.5" stroke="currentColor"/>
				<path d="M23.7319 33.1866C23.4781 33.4404 23.0666 33.441 22.8128 33.1873C22.5589 32.9334 22.5589 32.5213 22.8128 32.2675L31.1577 23.9226L23.8173 23.9226C23.4586 23.9223 23.1676 23.6315 23.1675 23.2728C23.1675 22.9138 23.459 22.6223 23.818 22.6223L32.7273 22.6223C33.086 22.6223 33.3767 22.9134 33.377 23.2721L33.3777 32.182C33.3777 32.541 33.0862 32.8325 32.7273 32.8325C32.3683 32.8325 32.0768 32.541 32.0768 32.182L32.0768 24.8417L23.7319 33.1866Z" fill="currentColor"/>
			</svg>
		</span>
	</a>
</article>
