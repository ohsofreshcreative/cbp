<section
    data-gsap-anim="section"
    @if(!empty($section_id)) id="{{ $section_id }}" @endif
    @class(['b-myth relative -smt text-white py-14 md:py-24',
        $sectionClass => filled($sectionClass),
        $section_class => filled($section_class),
        $background => filled($background) && $background !== 'none',
    ])>
    <div class="__wrapper c-main">
        <div class="__top mx-auto max-w-2xl text-center mb-10 md:mb-16">
            @if (!empty($g_myth['header']))
                <h2 class="__heading text-h2 text-white mt-0 mb-4">{{ $g_myth['header'] }}</h2>
            @endif
            @if (!empty($g_myth['text']))
                <p class="__text m-0 text-base md:text-lg leading-snug text-neutral-300 whitespace-pre-line">{{ $g_myth['text'] }}</p>
            @endif
        </div>

        @if ($r_myth)
            <div @class(['__cards grid grid-cols-1 md:grid-cols-2', 'gap-8 lg:gap-12' => $gap, 'gap-6 lg:gap-8' => !$gap])>
                @foreach ($r_myth as $item)
                    @if (!empty($item['myth']) || !empty($item['explanation']))
                        <article class="__card min-w-0 overflow-hidden rounded-3xl bg-neutral-700 flex flex-col">
                            <div class="__myth rounded-3xl bg-neutral-800 p-6 md:p-10">
                                <h3 class="__label text-h6 text-white mt-0 mb-4">Mit</h3>
                                @if (!empty($item['myth']))
                                    <div class="__quote flex items-start gap-2">
                                        <svg class="__icon shrink-0 size-6 text-primary" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 5H10L7 12H10V21H2V12L4 5ZM16 5H22L19 12H22V21H14V12L16 5Z" /></svg>
                                        <p class="__text m-0 text-base md:text-xl leading-snug text-neutral-300 whitespace-pre-line wrap-anywhere">{{ $item['myth'] }}</p>
                                    </div>
                                @endif
                            </div>
                            @if (!empty($item['explanation']))
                                <div class="__explanation p-6 md:px-10 md:pt-6 md:pb-10">
                                    <h4 class="__label text-h6 text-primary mt-0 mb-4">Wyjaśnienie</h4>
                                    <p class="__text m-0 text-base md:text-xl leading-snug text-neutral-300 whitespace-pre-line wrap-anywhere">{{ $item['explanation'] }}</p>
                                </div>
                            @endif
                        </article>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
