<section
    data-gsap-anim="section"
    @if(!empty($section_id)) id="{{ $section_id }}" @endif
    @class(['b-proces relative -smt overflow-clip text-white pb-14 md:pb-24',
        $sectionClass => filled($sectionClass),
        $section_class => filled($section_class),
        $background => filled($background) && $background !== 'none',
    ])>
    <div class="__wrapper c-main">
        <div class="__top mb-10 md:mb-14">
            @if (!empty($g_proces['header']))
                <h2 class="__heading text-h2 text-white m-0">{{ $g_proces['header'] }}</h2>
            @endif
            @if (!empty($g_proces['txt']))
                <div class="__txt mt-5 max-w-3xl text-neutral-300 [&_p:last-child]:mb-0">{!! wp_kses_post($g_proces['txt']) !!}</div>
            @endif
        </div>

        @if ($r_proces)
            <div class="__slider swiper !overflow-visible" role="region" aria-label="{{ $g_proces['header'] ?? 'Proces współpracy' }}">
                <div class="__slides swiper-wrapper !items-stretch">
                    @foreach ($r_proces as $item)
                        <article class="__card swiper-slide !h-auto !w-5/6 sm:!w-2/3 md:!w-2/5 xl:!w-1/3 rounded-2xl bg-linear-to-b from-neutral-700 to-neutral-800 px-6 py-10 md:px-8 md:py-14">
                            <div class="__number text-primary text-5xl md:text-6xl font-header leading-none mb-4">{{ filled($item['number'] ?? null) ? $item['number'] : sprintf('%02d', $loop->iteration) }}</div>
                            @if (!empty($item['title']))
                                <h3 class="__title text-h6 text-white mt-0 mb-3">{{ $item['title'] }}</h3>
                            @endif
                            @if (!empty($item['txt']))
                                <div class="__txt text-base text-neutral-300 leading-normal [&_p]:mt-0 [&_p]:mb-3 [&_p:last-child]:mb-0">{!! wp_kses_post($item['txt']) !!}</div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
            <div class="__navigation relative z-10 flex gap-4 mt-10 md:mt-12">
                <button type="button" class="__prev flex size-14 shrink-0 items-center justify-center rounded-full bg-primary text-neutral-800 cursor-pointer hover:bg-primary-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary disabled:opacity-30 disabled:cursor-default" aria-label="Poprzedni etap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12H4m7-7-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </button>
                <button type="button" class="__next flex size-14 shrink-0 items-center justify-center rounded-full bg-primary text-neutral-800 cursor-pointer hover:bg-primary-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary disabled:opacity-30 disabled:cursor-default" aria-label="Następny etap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16m-7-7 7 7-7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </button>
            </div>
        @endif
    </div>
</section>
