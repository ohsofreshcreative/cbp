<section
    data-gsap-anim="section"
    @if(!empty($section_id)) id="{{ $section_id }}" @endif
    @class(['b-about relative -smt isolate overflow-clip bg-neutral-50 text-neutral-800 py-14 md:py-24 xl:py-28',
        $sectionClass => filled($sectionClass),
        $section_class => filled($section_class),
        $background => filled($background) && $background !== 'none',
    ])>
    <div class="__glow absolute -z-10 pointer-events-none top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 size-96 md:size-160 rounded-full bg-radial from-primary/35 via-primary/15 to-transparent blur-3xl" aria-hidden="true"></div>

    <div class="__wrapper c-main relative">
        <div @class(['__col grid grid-cols-1 lg:grid-cols-2 items-center gap-10', 'lg:gap-24' => $gap, 'lg:gap-16' => !$gap])>
            <div class="__content order1 min-w-0">
                @if (!empty($g_about['label']))
                    <p class="__label flex items-center gap-2.5 text-lg mt-0 mb-5">
                        <svg class="text-primary-700 shrink-0" width="30" height="26" viewBox="0 0 30 26" fill="none" aria-hidden="true"><path d="M1 14h4L8 4l4 19 4-17 4 14 3-12 3 6h3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" /></svg>
                        <span>{{ $g_about['label'] }}</span>
                    </p>
                @endif
                @if (!empty($g_about['header']))
                    <h2 class="__heading text-h2 text-neutral-800 leading-tight mt-0 mb-5">{{ $g_about['header'] }}</h2>
                @endif
                @if (!empty($g_about['text']))
                    <div @class(['__txt text-base md:text-lg text-neutral-600 leading-snug [&_p]:mt-0 [&_p]:mb-4 [&_p:last-child]:mb-0 [&_ul]:my-4 [&_li]:mb-2', '[&_ul]:list-none [&_ul]:pl-0' => $nolist, '[&_ul]:list-disc [&_ul]:pl-5' => !$nolist])>{!! wp_kses_post($g_about['text']) !!}</div>
                @endif
                @if (!empty($g_about['button1']['url']) || !empty($g_about['button2']['url']))
                    <div class="__buttons flex flex-wrap gap-4 mt-7">
                        @foreach (['button1', 'button2'] as $buttonKey)
                            @php($button = $g_about[$buttonKey] ?? [])
                            @if (!empty($button['url']))
                                <x-button :href="$button['url']" :variant="$buttonKey === 'button1' ? 'primary' : 'secondary'" :target="$button['target'] ?? '_self'" :rel="($button['target'] ?? '') === '_blank' ? 'noopener noreferrer' : null" class="rounded-3xl px-10 py-4">
                                    {{ $button['title'] ?? 'Dowiedz się więcej' }}
                                </x-button>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="__media order2 grid grid-cols-2 items-center gap-6 md:gap-10">
                @if ($r_about)
                    <div class="__logos flex flex-col items-center justify-center gap-10 md:gap-16">
                        @foreach ($r_about as $item)
                            @if (!empty($item['image']['url']))
                                <img class="__logo block w-full max-h-36 object-contain" src="{{ $item['image']['url'] }}" alt="{{ ($item['image']['alt'] ?? '') ?: ($item['title'] ?? '') }}" loading="lazy" />
                            @elseif (!empty($item['title']))
                                <p class="__name text-center text-neutral-600">{{ $item['title'] }}</p>
                            @endif
                        @endforeach
                    </div>
                @endif
                @if (!empty($g_about['image']['url']))
                    <figure @class(['__img m-0 w-full overflow-hidden rounded-3xl aspect-2/3', 'col-start-2' => !$r_about])>
                        <picture class="block size-full">
                            <img class="size-full object-cover object-top" src="{{ $g_about['image']['url'] }}" alt="{{ $g_about['image']['alt'] ?? '' }}" loading="lazy" />
                        </picture>
                    </figure>
                @endif
            </div>
        </div>
    </div>
</section>
