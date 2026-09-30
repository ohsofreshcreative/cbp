<!--- reach -->

<section
  data-gsap-anim="section"
  @if(!empty($section_id)) id="{{ $section_id }}" @endif
  @class(['b-reach relative isolate grid grid-cols-1 overflow-hidden py-0!',
    $sectionClass => filled($sectionClass),
    $section_class => filled($section_class),
    $background => filled($background) && $background !== 'none',
  ])>

  @if (!empty($g_reach['image']['url']))
    <figure class="__img absolute inset-0 m-0">
      <img
        src="{{ $g_reach['image']['url'] }}"
        alt="{{ $g_reach['image']['alt'] ?? '' }}"
        @if (!empty($g_reach['image']['ID']))
          srcset="{{ wp_get_attachment_image_srcset($g_reach['image']['ID'], 'full') ?: '' }}"
          sizes="100vw"
        @endif
        class="size-full object-cover"
        loading="lazy"
        decoding="async">
    </figure>
  @endif
  <div class="pointer-events-none absolute inset-0 bg-black/50" aria-hidden="true"></div>

  <div class="__wrapper c-main relative z-1 col-start-1 row-start-1 py-24 md:py-36 lg:py-52">
    @if (!empty($g_reach['header']))
      <h2 data-gsap-element="header" class="__header text-center text-white">
        {!! wp_kses($g_reach['header'], ['strong' => [], 'b' => [], 'em' => [], 'i' => [], 'br' => []]) !!}
      </h2>
    @endif
  </div>

  <div class="pointer-events-none relative col-start-1 row-start-2 -mt-12 [background:inherit] [clip-path:ellipse(160%_100%_at_25%_100%)] lg:-mt-24 lg:[clip-path:ellipse(85%_100%_at_25%_100%)]" aria-hidden="true">
    <div class="absolute inset-y-0 left-0 bg-radial-[at_0%_100%] from-primary/30 to-transparent"></div>
  </div>

  <div class="__wrapper c-main relative z-1 col-start-1 row-start-2 grid section-gap -spb lg:grid-cols-2">
    <div class="__content">
      @if (!empty($g_reach['subheader']))
        <h3 data-gsap-element="header" class="__subheader m-title text-secondary">{{ $g_reach['subheader'] }}</h3>
      @endif
      @if (!empty($g_reach['text']))
        <div data-gsap-element="txt" class="__txt text-secondary-400">
          {!! $g_reach['text'] !!}
        </div>
      @endif
    </div>

    @if (!empty($g_reach['button1']['url']))
      <div class="__cta self-start justify-self-start rounded-(--btns-radius) bg-primary p-6 lg:-translate-y-20 lg:justify-self-end lg:p-12">
        <x-button
          :href="$g_reach['button1']['url']"
          :target="$g_reach['button1']['target'] ?? null"
          :rel="($g_reach['button1']['target'] ?? '') === '_blank' ? 'noopener noreferrer' : null"
          variant="white"
          class="inline-flex! w-auto! items-center gap-4 py-2! pr-2! pl-6! whitespace-normal! text-primary-900! hover:text-white!"
          data-gsap-element="btn">
          <span>{{ $g_reach['button1']['title'] }}</span>
          <span class="flex size-12 shrink-0 items-center justify-center rounded-(--btns-radius) bg-primary text-white" aria-hidden="true">
            <x-icon.arrow-up class="-rotate-135" />
          </span>
        </x-button>
      </div>
    @endif
  </div>
</section>
