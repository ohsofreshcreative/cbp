<!--- about preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">O nas</div>
      <span class="acf-preview__slug">acf/about</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_about['image']['ID']))
    <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($g_about['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
    @elseif (!empty($g_about['image']['url']))
    <figure class="acf-preview__media m-0"><img src="{{ $g_about['image']['url'] }}" alt="{{ $g_about['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
    @endif
    @if (!empty($g_about['label']))
    <p>{{ $g_about['label'] }}</p>
    @endif
    @if (!empty($g_about['header']))
    <p class="text-h5">{{ $g_about['header'] }}</p>
    @endif
    @if (!empty($g_about['text']))
    <div>{!! $g_about['text'] !!}</div>
    @endif
    <div class="acf-preview-actions">
    @if (!empty($g_about['button1']['title']))
    <span class="acf-preview-button">{{ $g_about['button1']['title'] }}</span>
    @endif
    @if (!empty($g_about['button2']['title']))
    <span class="acf-preview-button">{{ $g_about['button2']['title'] }}</span>
    @endif
    </div>
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($r_about ?? []), 0, 4) as $item)
      <div class="acf-preview__card">
        @if (!empty($item['image']['ID']))
        <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($item['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
        @elseif (!empty($item['image']['url']))
        <figure class="acf-preview__media m-0"><img src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
        @endif
        @if (!empty($item['title']))
        <p>{{ $item['title'] }}</p>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</div>
