<!doctype html>
<html @php(language_attributes())>

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@php(do_action('get_header'))
	@php(wp_head())

	{{-- Fonts --}}
	<link rel="stylesheet" href="https://use.typekit.net/eax3wfy.css">

	{{-- Styles --}}
	@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body @php(body_class())>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M5TV295L"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	@php(wp_body_open())

	<div id="app">

		@include('sections.header')

		@if (function_exists('is_woocommerce') && (is_shop() || is_product_category() || is_product_tag()))

		@yield('content')

		@elseif (function_exists('is_product') && is_product())

		<main id="main" class="main -menu-mt">
			@yield('content')
		</main>

		@else

		<main id="main" class="main">
			@yield('content')
		</main>

		@endif

		@include('sections.footer')
	</div>

	@php(do_action('get_footer'))
	@php(wp_footer())

</body>

</html>