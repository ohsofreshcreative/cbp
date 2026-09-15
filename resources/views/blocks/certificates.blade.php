<section
    data-gsap-anim="section"
    @if(!empty($section_id)) id="{{ $section_id }}" @endif
    @class(['b-certificates relative -smt isolate overflow-clip bg-neutral-950 text-white py-14 md:py-24',
        $sectionClass => filled($sectionClass),
        $section_class => filled($section_class),
        $background => filled($background) && $background !== 'none',
    ])>
    <div class="__glow absolute -z-10 pointer-events-none -top-48 -left-64 size-128 rounded-full bg-radial from-primary/20 to-transparent blur-3xl" aria-hidden="true"></div>
    <div class="__wrapper c-main relative">
        <div class="__top mb-10 md:mb-14 max-w-3xl">
            @if (!empty($g_certificates['header']))
                <h2 class="__heading text-h2 text-white mt-0 mb-4">{{ $g_certificates['header'] }}</h2>
            @endif
            @if (!empty($g_certificates['text']))
                <p class="__text text-base md:text-lg text-neutral-300 leading-snug whitespace-pre-line m-0">{{ $g_certificates['text'] }}</p>
            @endif
        </div>
        @if (!empty($g_certificates['gallery']))
            <div @class(['__gallery lightbox-gallery grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4', 'gap-8 md:gap-12' => $gap, 'gap-6 md:gap-8' => !$gap])>
                @foreach ($g_certificates['gallery'] as $image)
                    @if (!empty($image['url']))
                        @php
                            $label = ($image['alt'] ?? '') ?: ($image['title'] ?? 'Certyfikat');
                        @endphp
                        <a class="__card group relative block aspect-3/2 overflow-hidden rounded-3xl bg-linear-to-b from-neutral-900 to-neutral-800 px-8 pt-8 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary" href="{{ $image['url'] }}" aria-label="Powiększ: {{ $label }}" data-caption="{{ $image['caption'] ?? '' }}">
                            @if (!empty($image['ID']))
                                {!! wp_get_attachment_image($image['ID'], 'medium_large', false, ['class' => '__thumbnail block w-3/4 max-w-full h-auto mx-auto rounded-t-2xl transition-transform duration-300 motion-reduce:transition-none group-hover:scale-105', 'loading' => 'lazy', 'alt' => $label]) !!}
                            @else
                                <img class="__thumbnail block w-full max-w-full h-auto mx-auto rounded-t-2xl transition-transform duration-300 motion-reduce:transition-none group-hover:scale-105" src="{{ $image['sizes']['medium_large'] ?? $image['url'] }}" alt="{{ $label }}" loading="lazy" />
                            @endif
                            <span class="__expand absolute right-4 top-4 flex size-9 items-center justify-center rounded-full bg-primary group-hover:bg-primary-600 transition-all text-neutral-800" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M8 3H3v5m13-5h5v5M3 16v5h5m13-5v5h-5M3 3l6 6m12-6-6 6M3 21l6-6m12 6-6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>
        @elseif (!empty($is_preview))
            <p>Dodaj zdjęcia JPG w menu „Certyfikaty”, aby wyświetlić galerię.</p>
        @endif
    </div>
</section>
