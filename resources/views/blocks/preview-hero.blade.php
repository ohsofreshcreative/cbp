<!--- hero preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Hero</div>
      <span class="acf-preview__slug">acf/hero</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_hero['image']['ID']))
    <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($g_hero['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
    @elseif (!empty($g_hero['video']))
    <p>Wideo w tle</p>
    @endif
    @if (!empty($g_hero['label']))
    <p>{{ $g_hero['label'] }}</p>
    @endif
    @if (!empty($g_hero['title']))
    <p class="text-h5">{{ $g_hero['title'] }}</p>
    @endif
    @if (!empty($g_hero['text']))
    <div>{!! $g_hero['text'] !!}</div>
    @endif
    <div class="acf-preview-actions">
      @if (!empty($g_hero['button1']['title']))
      <span class="acf-preview-button">{{ $g_hero['button1']['title'] }}</span>
      @endif
      @if (!empty($g_hero['button2']['title']))
      <span class="acf-preview-button">{{ $g_hero['button2']['title'] }}</span>
      @endif
    </div>
  </div>
</div>
