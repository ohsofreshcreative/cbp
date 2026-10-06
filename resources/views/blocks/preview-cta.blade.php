<!--- cta preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Wezwanie do działania</div>
      <span class="acf-preview__slug">acf/cta</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_octa['image']['url']))
    <figure class="acf-preview__media m-0">
      <img class="h-20 w-32 object-cover" src="{{ $g_octa['image']['url'] }}" alt="{{ $g_octa['image']['alt'] ?? '' }}">
    </figure>
    @endif
    @if (!empty($g_octa['header']))
    <p class="text-h5">{{ $g_octa['header'] }}</p>
    @endif
    @if (!empty($g_octa['txt']))
    <div>{!! $g_octa['txt'] !!}</div>
    @endif
    @if ($form && !empty($g_octa['shortcode']))
    <p>Formularz kontaktowy</p>
    @endif
    <div class="acf-preview-actions">
      @if (!empty($g_octa['button1']['title']))
      <span class="acf-preview-button">{{ $g_octa['button1']['title'] }}</span>
      @endif
      @if (!empty($g_octa['button2']['title']))
      <span class="acf-preview-button">{{ $g_octa['button2']['title'] }}</span>
      @endif
    </div>
  </div>
</div>
