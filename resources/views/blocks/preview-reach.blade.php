<!--- reach preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Zasięg</div>
      <span class="acf-preview__slug">acf/reach</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_reach['image']['ID']))
    <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($g_reach['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
    @elseif (!empty($g_reach['image']['url']))
    <figure class="acf-preview__media m-0"><img src="{{ $g_reach['image']['url'] }}" alt="{{ $g_reach['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
    @endif
    @if (!empty($g_reach['header']))
    <p class="text-h5">{{ $g_reach['header'] }}</p>
    @endif
    @if (!empty($g_reach['subheader']))
    <p>{{ $g_reach['subheader'] }}</p>
    @endif
    @if (!empty($g_reach['text']))
    <div>{!! $g_reach['text'] !!}</div>
    @endif
    <div class="acf-preview-actions">
    @if (!empty($g_reach['button1']['title']))
    <span class="acf-preview-button">{{ $g_reach['button1']['title'] }}</span>
    @endif
    </div>
  </div>
</div>
