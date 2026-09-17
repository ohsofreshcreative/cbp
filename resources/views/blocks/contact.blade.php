<!--- contact --->

@php
	$g_contact_1 = $g_contact_1 ?: [];
	$g_contact_2 = $g_contact_2 ?: [];
	$benefits = $g_contact_1['benefits'] ?? [];
	$theme_uri = get_template_directory_uri();
@endphp

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-contact relative isolate overflow-clip -menu-pt -spb' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if (!empty($g_contact_1['image']['url']))
	<figure class="absolute inset-0 -z-20 m-0">
		<img src="{{ $g_contact_1['image']['url'] }}" alt="{{ $g_contact_1['image']['alt'] ?? '' }}" class="size-full object-cover opacity-20" />
	</figure>
	@endif

	<div class="__glow pointer-events-none absolute left-[-150px] top-[150px] -z-10 size-[600px]" aria-hidden="true">
		<div class="absolute -inset-[30%]">
			<img class="block size-full max-w-none" src="{{ $theme_uri }}/resources/images/contact-glow-gold.svg" alt="" width="960" height="960" />
		</div>
	</div>
	<div class="__glow pointer-events-none absolute right-[-200px] top-[-100px] -z-10 size-[700px]" aria-hidden="true">
		<div class="absolute -inset-[31.43%]">
			<img class="block size-full max-w-none" src="{{ $theme_uri }}/resources/images/contact-glow-soft.svg" alt="" width="1140" height="1140" />
		</div>
	</div>

	<div class="__wrapper c-main relative z-10 pt-10">
		<div class="__breadcrumb" data-gsap-element="header">
			@if (function_exists('yoast_breadcrumb'))
			{!! yoast_breadcrumb('<p id="breadcrumbs">', '</p>') !!}
			@else
			<p>
				<a href="{{ home_url('/') }}">Homepage</a>
				<span>Kontakt</span>
			</p>
			@endif
		</div>

		<div class="relative grid grid-cols-1 lg:grid-cols-2 items-center">
			<div class="__content flex flex-col gap-8">
				<div>
					@if (!empty($g_contact_1['header']))
					<h1 data-gsap-element="header" class="text-h2 text-white m-header [&_strong]:text-primary">{!! wp_kses($g_contact_1['header'], ['strong' => [], 'br' => []]) !!}</h1>
					@endif

					@if (!empty($g_contact_1['text']))
					<div data-gsap-element="txt" class="__txt text-neutral-400">{!! wp_kses_post($g_contact_1['text']) !!}</div>
					@endif
				</div>

				<div class="__links flex flex-col gap-4">
					@if (!empty($g_contact_1['mail']))
					<a data-gsap-element="txt" class="__link flex items-center gap-4 text-primary" href="mailto:{{ $g_contact_1['mail'] }}">
						<img class="shrink-0" src="{{ $theme_uri }}/resources/images/contact-icon-mail.svg" alt="" width="28" height="28" />
						<span>{{ $g_contact_1['mail'] }}</span>
					</a>
					@endif
					@if (!empty($g_contact_1['phone']))
					<a data-gsap-element="txt" class="__link flex items-center gap-4 text-primary" href="tel:{{ preg_replace('/[^0-9+]/', '', $g_contact_1['phone']) }}">
						<img class="shrink-0" src="{{ $theme_uri }}/resources/images/contact-icon-phone.svg" alt="" width="28" height="28" />
						<span>{{ $g_contact_1['phone'] }}</span>
					</a>
					@endif
					@if (!empty($g_contact_1['address']))
					<p data-gsap-element="txt" class="__link flex items-center gap-4 text-primary m-0">
						<img class="shrink-0" src="{{ $theme_uri }}/resources/images/contact-icon-pin.svg" alt="" width="28" height="28" />
						<span>{!! wp_kses($g_contact_1['address'], ['br' => []]) !!}</span>
					</p>
					@endif
				</div>

				@if (!empty($benefits))
				<ul class="__benefits flex flex-col gap-4 list-none p-0 m-0">
					@foreach ($benefits as $benefit)
					@if (!empty($benefit['text']))
					<li class="__benefit flex items-center gap-3" data-gsap-element="txt">
						<img class="shrink-0" src="{{ $theme_uri }}/resources/images/contact-icon-check.svg" alt="" width="18" height="18" />
						<span>{{ $benefit['text'] }}</span>
					</li>
					@endif
					@endforeach
				</ul>
				@endif
			</div>

			@if (!empty($g_contact_2['shortcode']))
			<div data-gsap-element="form" class="__form min-w-0 radius border border-primary bg-neutral-900/80 p-6 md:p-12
				[&_label]:block [&_label]:text-primary-200
				[&_p]:mb-4 [&_p:last-child]:mb-0
				[&_input:not([type=checkbox]):not([type=submit])]:w-full [&_input:not([type=checkbox]):not([type=submit])]:rounded-xl [&_input:not([type=checkbox]):not([type=submit])]:border-primary/20 [&_input:not([type=checkbox]):not([type=submit])]:bg-neutral-950/80 [&_input:not([type=checkbox]):not([type=submit])]:text-white [&_input:not([type=checkbox]):not([type=submit])]:px-4 [&_input:not([type=checkbox]):not([type=submit])]:py-4
				[&_textarea]:w-full [&_textarea]:h-36 [&_textarea]:rounded-xl [&_textarea]:border-primary/20 [&_textarea]:bg-neutral-950/80 [&_textarea]:text-white [&_textarea]:p-4
				[&_input[type=checkbox]]:rounded-lg [&_input[type=checkbox]]:border-primary [&_input[type=checkbox]]:text-primary [&_input[type=checkbox]]:bg-neutral-950
				[&_input[type=submit]]:w-full [&_input[type=submit]]:rounded-3xl [&_input[type=submit]]:bg-primary [&_input[type=submit]]:text-neutral-950 [&_input[type=submit]]:py-4 [&_input[type=submit]]:cursor-pointer
				[&_.wpcf7-list-item]:ml-0 [&_.wpcf7-acceptance_label]:text-white [&_.wpcf7-response-output]:text-white">
				@if (!empty($g_contact_2['title']))
				<h2 class="text-h5 text-white mt-0 mb-6">{{ $g_contact_2['title'] }}</h2>
				@endif
				{!! do_shortcode($g_contact_2['shortcode']) !!}
			</div>
			@endif
		</div>
	</div>

</section>
