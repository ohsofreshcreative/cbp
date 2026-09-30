<!--- problem -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class(['b-problem relative rounded-t-3xl -smt isolate overflow-hidden',
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($r_problem))
		<div class="__list grid gap-8 lg:gap-20">
			@foreach ($r_problem as $item)
			<div data-gsap-element="card" class="__row grid grid-cols-1 md:grid-cols-2 items-center gap-8 lg:gap-20">
				<div @class(['__content relative z-10 min-w-0 only:col-span-full', 'md:order-2'=> $loop->even])>
					@if ($loop->first && !empty($g_problem['header']))
					<p class="__label flex items-center gap-2 text-secondary-700!">
						<x-icon.ekg class="w-8 h-7 shrink-0 text-primary-800" />
						<span class="font-medium!">{{ $g_problem['header'] }}</span>
					</p>
					@endif

					@if (!empty($item['header']))
					@if ($loop->first)
					<h2 data-gsap-element="header" class="m-header text-secondary-700!">{{ $item['header'] }}</h2>
					@else
					<h3 data-gsap-element="header" class="m-header text-secondary-700!">{{ $item['header'] }}</h3>
					@endif
					@endif

					@if (!empty($item['text']))
					<div data-gsap-element="txt" class="__txt">
						{!! $item['text'] !!}
					</div>
					@endif

					@if (!empty($item['button1']['url']))
					<div class="inline-buttons m-btn">
						<x-button :href="$item['button1']['url']" variant="secondary" :target="$item['button1']['target'] ?? '_self'" data-gsap-element="btn">
							{{ $item['button1']['title'] }}
						</x-button>
					</div>
					@endif
				</div>

				@if (!empty($item['image']['url']))
				<div @class(['__media relative w-full lg:w-5/6', 'md:order-1'=> $loop->even])>
					@if ($loop->iteration === 2)
					<div class="pointer-events-none absolute -left-1/2 -top-1/4 size-full bg-radial from-primary/15 to-transparent" aria-hidden="true"></div>
					<div class="pointer-events-none absolute -left-2/3 top-1/2 w-3/2 -translate-y-1/2 text-primary" aria-hidden="true">
						<svg class="block w-full h-auto" xmlns="http://www.w3.org/2000/svg" width="771" height="633" viewBox="0 0 771 633" fill="none">
							<path opacity="0.6" d="M125.072 17.2129C127.845 7.42125 137.021 0.922278 147.211 1.54004V1.54102C157.335 2.20892 165.562 9.93863 166.999 19.9756V19.9766L215.538 365.964L218.503 366.002L260.444 114.598V114.597C262.116 104.529 270.662 97.0285 280.891 96.6367C291.243 96.3975 300.173 103.237 302.514 113.162L364.862 377.597L366.162 383.107L367.762 377.676L421.108 196.532V196.531C423.929 186.933 433.063 180.58 442.905 181.152H442.906C452.851 181.726 461.119 189.082 462.791 198.915L462.792 198.919L508.006 460.135L510.97 460.082L562.526 83.0264C563.866 73.2311 571.658 65.6403 581.454 64.5898C591.304 63.5391 600.482 69.2677 603.928 78.542L681.209 289.317L681.57 290.301H748.035C759.888 290.301 769.5 299.948 769.5 311.795C769.5 323.642 759.888 333.29 748.035 333.29H666.608C657.766 333.29 649.844 327.831 646.633 319.593L646.483 319.198L595.438 180.058L592.544 180.371L533.418 612.817C531.985 623.319 523.154 631.194 512.553 631.397H511.218L511.176 631.479C501.151 631.055 492.68 623.698 490.964 613.687L490.963 613.685L437.259 303.517L434.342 303.35L384.371 473.096C381.595 482.47 372.989 488.814 363.195 488.527C353.486 488.24 345.123 481.454 342.926 472.007L342.925 472.002L286.867 234.292L283.928 234.39L236.157 520.64C234.386 531.116 225.293 538.794 214.749 538.604H214.742C204.175 538.46 195.239 530.625 193.806 520.125L193.805 520.119L139.64 134.225L136.711 134.025L84.4395 319.204C81.8079 328.477 73.3905 334.874 63.7861 334.874H-4.03516C-15.8881 334.874 -25.5 325.226 -25.5 313.379C-25.4998 301.532 -15.888 291.885 -4.03516 291.885H47.6338L47.9424 290.792L125.071 17.2119L125.072 17.2129Z" stroke="currentColor" stroke-width="3" vector-effect="non-scaling-stroke" />
						</svg>
					</div>
					@endif
					<figure data-gsap-element="img" class="__img relative m-0 aspect-square overflow-hidden rounded-full">
						<picture class="block size-full">
							<img class="size-full object-cover" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" loading="lazy">
						</picture>
					</figure>
				</div>
				@endif
			</div>
			@endforeach
		</div>
		@elseif (!empty($g_problem['header']))
		<h2 data-gsap-element="header" class="m-header text-secondary-700!">{{ $g_problem['header'] }}</h2>
		@endif
	</div>
</section>
