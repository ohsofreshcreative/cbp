<!-- banner preview -->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Hero - Podstrona</div>
      <span class="acf-preview__slug">acf/banner</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_banner['image']['ID']))
    <figure class="acf-preview__media m-0">
      {!! wp_get_attachment_image($g_banner['image']['ID'], 'thumbnail', false, ['class' => 'h-24 w-full object-cover']) !!}
    </figure>
    @endif
    @if (function_exists('yoast_breadcrumb'))
    <p>Breadcrumbs</p>
    @endif
    @if (!empty($g_banner['title']))
    <p class="text-h5">{{ $g_banner['title'] }}</p>
    @endif
    @if (!empty($g_banner['text']))
    <div>{!! $g_banner['text'] !!}</div>
    @endif
    <div class="acf-preview-actions">
      @if (!empty($g_banner['button1']['title']))
      <span class="acf-preview-button">{{ $g_banner['button1']['title'] }}</span>
      @endif
      @if (!empty($g_banner['button2']['title']))
      <span class="acf-preview-button">{{ $g_banner['button2']['title'] }}</span>
      @endif
    </div>
  </div>
</div>
