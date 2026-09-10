<!-- hero --->

<section
    data-gsap-anim="section"
    @if(!empty($section_id)) id="{{ $section_id }}" @endif
    @class([ 'b-hero relative -spt overflow-visible' ,
    $sectionClass=> filled($sectionClass),
    $section_class => filled($section_class),
    $background => filled($background) && $background !== 'none',
    ])>

    @if (!empty($g_hero['video']))
    <video class="absolute inset-0 w-full h-full object-cover z-0" autoplay loop muted playsinline>
        <source src="{{ $g_hero['video'] }}" type="video/mp4">
    </video>
    @elseif(!empty($g_hero['image']))
    <figure class="absolute inset-0 w-full h-full z-0 m-0">
        <picture class="w-full h-full">
            <img src="{{ $g_hero['image']['url'] }}" alt="{{ $g_hero['image']['alt'] }}" class="w-full h-full object-cover" />
        </picture>
    </figure>
    @endif

    @if (!empty($g_hero['video']) || !empty($g_hero['image']))
    <div class="absolute inset-0 z-1 pointer-events-none" style="background: linear-gradient(90deg, #171F87 5.84%, rgba(23, 31, 135, 0.20) 100.47%);"></div>
    @endif

    <div class="absolute bottom-0 left-0 w-full pointer-events-none z-10 hidden md:block">
        <div class="absolute bottom-0 left-0 w-[55%] h-[122px] overflow-hidden">
            <svg class="absolute right-0 bottom-0 h-[122px] w-[1192px] shrink-0" xmlns="http://www.w3.org/2000/svg" width="1192" height="122" viewBox="0 0 1192 122" fill="none">
                <path opacity="0.8" fill-rule="evenodd" clip-rule="evenodd" d="M1192 122H-13V0H1074.39C1139.36 0 1192 52.9982 1192 118.412V122Z" fill="url(#paint0_linear_53422_4170)" />
                <defs>
                    <linearGradient id="paint0_linear_53422_4170" x1="58" y1="61" x2="1192" y2="61" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#2682E8" />
                        <stop offset="1" stop-color="#171C84" />
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <div class="absolute bottom-0 right-0 w-[45%] h-[173px] translate-y-[173px] overflow-hidden">
            <svg class="absolute left-0 top-0 h-[173px] w-[1212px] -ml-[1px]" xmlns="http://www.w3.org/2000/svg" width="1212" height="173" viewBox="0 0 1212 173" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0H1212V173H167.104C74.7915 173 0 97.8468 0 5.08823V0Z" fill="url(#paint0_linear_53422_4169)" />
                <defs>
                    <linearGradient id="paint0_linear_53422_4169" x1="344.335" y1="86.5" x2="0.00057467" y2="86.5" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#2682E8" />
                        <stop offset="1" stop-color="#171C84" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <div class=" __wrapper c-main relative z-10">
        <div class="__content relative flex flex-col justify-center w-full md:w-10/12 lg:w-8/12 z-20 pt-48 pb-62">
            <h1 data-gsap-element="header" class="text-h2 text-white">
                {{ $g_hero['title'] }}
            </h1>
			@if (!empty($g_hero['text']))
            <div data-gsap-element="text" class="text-white mt-2">
                {!! $g_hero['text'] !!}
            </div>
			@endif

            <div class="inline-buttons m-btn">
                @if (!empty($g_hero['button1']))
                <x-button
                    :href="$g_hero['button1']['url']"
                    variant="secondary"
                    class=""
                    data-gsap-element="btn">
                    {{ $g_hero['button1']['title'] }}
                </x-button>
                @endif

                @if (!empty($g_hero['button2']))
                <x-button
                    :href="$g_hero['button2']['url']"
                    variant="white"
                    class=""
                    data-gsap-element="btn">
                    {{ $g_hero['button2']['title'] }}
                </x-button>
                @endif
            </div>
        </div>
    </div>

</section>