<footer class="footer relative z-10 isolate overflow-hidden bg-[#211A03] text-primary-200">
    <div class="__main relative py-14 md:pt-24 md:pb-20">
        @if (!empty($footer_decoration['url']))
            <img class="__decoration absolute right-0 bottom-0 h-full max-w-1/3 object-contain object-right-bottom opacity-20 pointer-events-none" src="{{ $footer_decoration['url'] }}" alt="" loading="lazy" />
        @endif
        <div class="__wrapper c-main relative">
            <div class="__top flex flex-col sm:flex-row sm:items-center justify-between gap-8 pb-7 border-b border-dotted border-primary/60">
                <a href="{{ home_url('/') }}" class="__brand flex items-center gap-5 text-white hover:text-primary">
                    @if (!empty($logo_footer['url']))
                        <img class="size-20 shrink-0 object-contain" src="{{ $logo_footer['url'] }}" alt="" loading="lazy" />
                    @endif
                    <span class="text-lg font-header">{{ get_bloginfo('name') }}</span>
                </a>
                <div class="__social flex items-center gap-5 text-primary">
                    @foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube'] as $network => $label)
                        @if (!empty($footer_social[$network]))
                            <a href="{{ $footer_social[$network] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}" class="hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
                                <svg class="size-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    @if ($network === 'facebook')
                                        <path d="M14 22v-9h3l.5-4H14V7c0-1.2.3-2 2-2h2V1.4A25 25 0 0 0 15 1c-3 0-5 1.8-5 5v3H7v4h3v9z" />
                                    @elseif ($network === 'instagram')
                                        <rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2" /><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2" /><circle cx="17.5" cy="6.5" r="1.2" />
                                    @else
                                        <path d="M23 7a3 3 0 0 0-2-2C18 4 6 4 3 5a3 3 0 0 0-2 2c-.7 3-.7 7 0 10a3 3 0 0 0 2 2c3 1 15 1 18 0a3 3 0 0 0 2-2c.7-3 .7-7 0-10ZM9 16V8l7 4-7 4Z" />
                                    @endif
                                </svg>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="__columns grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-16 pt-12 md:pt-14">
                @foreach ([[1, 3], [2, 4]] as $sidebars)
                    <div class="__menu space-y-8 [&_.widget-title]:text-primary-100 [&_.widget-title]:text-h5 [&_.widget-title]:cursor-default [&_.widget-title]:after:hidden! [&_.menu]:max-h-none [&_.menu]:overflow-visible [&_ul]:list-none [&_ul]:p-0 [&_ul]:m-0 [&_li]:mb-2 [&_a]:text-primary-200 [&_a:hover]:text-primary [&_.menu]:leading-normal">
                        @foreach ($sidebars as $sidebar)
                            @if (is_active_sidebar('sidebar-footer-' . $sidebar))
                                @php
                                    dynamic_sidebar('sidebar-footer-' . $sidebar);
                                @endphp
                            @endif
                        @endforeach
                    </div>
                @endforeach
                <div class="__contact min-w-0">
                    <h2 class="text-h5 text-primary-100 mt-0 mb-5">Kontakt</h2>
                    <div class="flex flex-col items-start gap-4">
                        @if (!empty($footer_contact['phone']))
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $footer_contact['phone']) }}" class="text-lg hover:text-primary">{{ $footer_contact['phone'] }}</a>
                        @endif
                        @if (!empty($footer_contact['email']))
                            <a href="mailto:{{ $footer_contact['email'] }}" class="text-lg wrap-anywhere hover:text-primary">{{ $footer_contact['email'] }}</a>
                        @endif
                        @if (!empty($footer_contact['address']))
                            <div class="__address text-sm [&_p]:m-0">{!! wp_kses_post($footer_contact['address']) !!}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="__bottom border-t border-primary/10 py-8 md:py-10">
        <div class="c-main flex flex-col md:flex-row items-center justify-center gap-5 md:gap-8 text-xs text-primary-200/70 text-center">
            <p class="m-0">Copyright ©{{ date('Y') }} {{ get_bloginfo('name') }}. All rights reserved.</p>
            <p class="m-0 flex flex-wrap items-center justify-center gap-2 md:border-l md:border-primary/60 md:pl-8">Designed &amp; Developed by
                <a target="_blank" rel="nofollow noopener noreferrer" href="https://www.ohsofresh.pl" title="OhSoFresh"><img class="w-28 h-auto" src="{{ get_template_directory_uri() }}/resources/images/ohsofresh.svg" alt="OhSoFresh" loading="lazy" /></a>
            </p>
        </div>
    </div>
</footer>
