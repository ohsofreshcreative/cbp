<section data-gsap-anim="section"
    @if(!empty($section_id)) id="{{ $section_id }}" @endif
    @class(['b-cta relative -smt isolate overflow-clip text-white py-14 md:py-24', $sectionClass => filled($sectionClass), $section_class => filled($section_class), $background => filled($background) && $background !== 'none'])>
    @if (!empty($g_octa['image']['url']))
        <img class="__background absolute inset-0 -z-20 size-full object-cover opacity-20" src="{{ $g_octa['image']['url'] }}" alt="" loading="lazy" />
    @endif
    <div class="__glow absolute -z-10 pointer-events-none top-1/2 -left-48 -translate-y-1/2 size-160 rounded-full bg-radial from-primary/20 to-transparent blur-3xl" aria-hidden="true"></div>
    <div class="__glow absolute -z-10 pointer-events-none -top-32 -right-48 size-160 rounded-full bg-radial from-primary/15 to-transparent blur-3xl" aria-hidden="true"></div>
    <div @class(['__wrapper c-main relative grid grid-cols-1 items-center gap-12', 'lg:grid-cols-2' => $form, 'lg:gap-24' => $gap, 'lg:gap-20' => !$gap])>
        <div class="__content order1 min-w-0">
            @if (!empty($g_octa['header']))
                <h2 class="__heading text-h2 text-white leading-tight mt-0 mb-5 [&_strong]:text-primary [&_strong]:font-normal">{!! wp_kses($g_octa['header'], ['strong' => [], 'br' => []]) !!}</h2>
            @endif
            @if (!empty($g_octa['txt']))
                <div class="__txt text-base md:text-lg text-neutral-400 leading-snug [&_p]:mt-0 [&_p]:mb-4 [&_p:last-child]:mb-0">{!! wp_kses_post($g_octa['txt']) !!}</div>
            @endif
            @if (!empty($g_octa['benefits']))
                <ul class="__benefits list-none p-0 mt-8 mb-0 space-y-4">
                    @foreach ($g_octa['benefits'] as $benefit)
                        @if (!empty($benefit['text']))
                            <li class="__benefit flex items-start gap-3 text-neutral-300">
                                <svg class="size-5 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                <span>{{ $benefit['text'] }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
            <div class="__buttons flex flex-wrap gap-4 mt-8">
                @if (!empty($g_octa['phone']))
                    <a class="inline-flex justify-center items-center rounded-3xl bg-primary text-neutral-950 font-semibold px-8 py-4 hover:bg-primary-300" href="tel:{{ preg_replace('/[^0-9+]/', '', $g_octa['phone']) }}">{{ $g_octa['phone'] }}</a>
                @endif
                @foreach (['button1', 'button2'] as $key)
                    @if (!empty($g_octa[$key]['url']))
                        <a class="inline-flex justify-center items-center rounded-3xl bg-primary text-neutral-950 px-8 py-4 hover:bg-primary-300" href="{{ $g_octa[$key]['url'] }}" target="{{ $g_octa[$key]['target'] ?? '_self' }}" rel="noopener noreferrer">{{ $g_octa[$key]['title'] }}</a>
                    @endif
                @endforeach
            </div>
        </div>
        @if ($form && !empty($g_octa['shortcode']))
            <div class="__form order2 min-w-0 rounded-3xl border border-primary/70 bg-neutral-900 p-6 md:p-10 shadow-xl
                [&_label]:block [&_label]:text-sm [&_label]:text-primary-200 [&_label]:leading-normal
                [&_p]:mb-5 [&_p:last-child]:mb-0
                [&_input:not([type=checkbox]):not([type=submit])]:w-full [&_input:not([type=checkbox]):not([type=submit])]:rounded-xl [&_input:not([type=checkbox]):not([type=submit])]:border-primary/20 [&_input:not([type=checkbox]):not([type=submit])]:bg-neutral-950 [&_input:not([type=checkbox]):not([type=submit])]:text-white [&_input:not([type=checkbox]):not([type=submit])]:px-4 [&_input:not([type=checkbox]):not([type=submit])]:py-4
                [&_textarea]:w-full [&_textarea]:h-36 [&_textarea]:rounded-xl [&_textarea]:border-primary/20 [&_textarea]:bg-neutral-950 [&_textarea]:text-white [&_textarea]:p-4
                [&_input[type=checkbox]]:rounded-lg [&_input[type=checkbox]]:border-primary [&_input[type=checkbox]]:text-primary [&_input[type=checkbox]]:bg-neutral-950
                [&_input[type=submit]]:w-full [&_input[type=submit]]:rounded-3xl [&_input[type=submit]]:bg-primary [&_input[type=submit]]:text-neutral-950 [&_input[type=submit]]:py-4 [&_input[type=submit]]:cursor-pointer
                [&_.wpcf7-list-item]:ml-0 [&_.wpcf7-acceptance_label]:text-neutral-300 [&_.wpcf7-response-output]:text-white">
                @if (!empty($g_octa['title']))
                    <h3 class="text-h5 text-white mt-0 mb-6">{{ $g_octa['title'] }}</h3>
                @endif
                {!! do_shortcode($g_octa['shortcode']) !!}
            </div>
        @endif
    </div>
</section>
