<section data-gsap-anim="section"
    @if(!empty($section_id)) id="{{ $section_id }}" @endif
    @class(['b-reviews relative -smt overflow-clip bg-neutral-950 text-white py-14 md:py-24', $sectionClass => filled($sectionClass), $section_class => filled($section_class), $background => filled($background) && $background !== 'none'])>
    <div class="__wrapper c-main grid grid-cols-1 lg:grid-cols-2 items-start gap-12 lg:gap-20">
        <div class="__intro relative z-10 min-w-0">
            @if (!empty($header))
                <h2 class="__heading text-h2 text-white leading-tight mt-0 mb-10">{{ $header }}</h2>
            @endif
            @if (is_numeric($reviews_rating))
                @php
                    $rating = max(0, min(5, (float) $reviews_rating));
                @endphp
                <div class="__rating flex items-center gap-4 mb-3" aria-label="Średnia ocen: {{ number_format($rating, 1, ',', '') }} na 5">
                    <span class="text-5xl font-header">{{ number_format($rating, 1, '.', '') }}</span>
                    <span class="relative text-3xl leading-none text-neutral-600" aria-hidden="true">
                        ★★★★★
                        <span class="absolute inset-y-0 left-0 overflow-hidden whitespace-nowrap text-amber-400" style="width: {{ $rating / 5 * 100 }}%">★★★★★</span>
                    </span>
                </div>
                <div class="__source flex items-center gap-2 text-neutral-300 text-lg">
                    <img src="{{ get_template_directory_uri() }}/resources/images/google.svg" alt="" class="size-6 shrink-0 object-contain" />
                    @if (!empty($reviews_google_url))
                        <a href="{{ $reviews_google_url }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary focus-visible:outline-2 focus-visible:outline-primary">Średnia ocen klientów na Google</a>
                    @else
                        <span>Średnia ocen klientów na Google</span>
                    @endif
                </div>
            @endif
        </div>
        @if ($r_reviews)
            <div class="__content min-w-0">
                <div class="__viewport overflow-hidden -mr-6 lg:-mr-96">
                    <div class="__slider reviews-swiper swiper !overflow-visible !mr-6 lg:!mr-96" role="region" aria-label="Opinie klientów">
                        <div class="__slides swiper-wrapper !items-stretch">
                            @foreach ($r_reviews as $card)
                                @php
                                    $name = $card['name'] ?? '';
                                    $initial = mb_strtoupper(mb_substr(trim($name), 0, 1));
                                @endphp
                                <article class="__card swiper-slide !h-auto !w-5/6 sm:!w-80 xl:!w-88 rounded-3xl bg-white text-neutral-900 p-7 md:p-8 min-h-72">
                                    <div class="__author flex items-center gap-4 mb-5">
                                        @if (!empty($card['image']['url']))
                                            <img src="{{ $card['image']['url'] }}" alt="" class="__avatar size-10 shrink-0 rounded-full object-cover" loading="lazy" />
                                        @else
                                            <span @class(['__avatar flex size-10 shrink-0 items-center justify-center rounded-full text-white text-lg', 'bg-pink-700' => $loop->odd, 'bg-slate-600' => $loop->even]) aria-hidden="true">{{ $initial }}</span>
                                        @endif
                                        <div class="__who min-w-0">
                                            <p class="text-h7 wrap-anywhere">{{ $name }}</p>
                                            @if (!empty($card['position']))
                                                <p class="text-sm text-neutral-600 m-0">{{ $card['position'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if (!empty($card['header']))
                                        <p class="__title font-medium mt-0 mb-3">{{ $card['header'] }}</p>
                                    @endif
                                    @if (!empty($card['txt']))
                                        <div class="__txt text-base text-neutral-500 leading-relaxed wrap-anywhere [&_p]:mt-0 [&_p]:mb-3 [&_p:last-child]:mb-0">{!! wp_kses_post($card['txt']) !!}</div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="__navigation flex gap-4 mt-10">
                    @foreach (['prev' => 'Poprzednia opinia', 'next' => 'Następna opinia'] as $direction => $label)
                        <button type="button" class="__{{ $direction }} flex size-14 items-center justify-center rounded-full bg-primary text-neutral-800 cursor-pointer hover:bg-primary-300 disabled:opacity-30 disabled:cursor-default focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary" aria-label="{{ $label }}">
                            <svg @class(['size-5', 'rotate-180' => $direction === 'prev']) viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16m-7-7 7 7-7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
