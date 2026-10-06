<!--- content preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Tekst oraz zdjęcie</div>
      <span class="acf-preview__slug">acf/content</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_content['image']['ID']))
    <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($g_content['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
    @endif
    @if (!empty($g_content['header']))
    <p class="text-h5">{{ $g_content['header'] }}</p>
    @endif
    @if (!empty($g_content['text']))
    <div>{!! $g_content['text'] !!}</div>
    @endif
    <div class="acf-preview-actions">
      @if (!empty($g_content['button1']['title']))
      <span class="acf-preview-button">{{ $g_content['button1']['title'] }}</span>
      @endif
      @if (!empty($g_content['button2']['title']))
      <span class="acf-preview-button">{{ $g_content['button2']['title'] }}</span>
      @endif
    </div>
  </div>
</div>
