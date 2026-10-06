<!--- myth preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Mity</div>
      <span class="acf-preview__slug">acf/myth</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_myth['image']['ID']))
    <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($g_myth['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
    @elseif (!empty($g_myth['image']['url']))
    <figure class="acf-preview__media m-0"><img src="{{ $g_myth['image']['url'] }}" alt="{{ $g_myth['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
    @endif
    @if (!empty($g_myth['label']))
    <p>{{ $g_myth['label'] }}</p>
    @endif
    @if (!empty($g_myth['header']))
    <p class="text-h5">{{ $g_myth['header'] }}</p>
    @endif
    @if (!empty($g_myth['text']))
    <p>{{ $g_myth['text'] }}</p>
    @endif
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($r_myth ?? []), 0, 4) as $item)
      <div class="acf-preview__card">
        <p>Mit</p>
        @if (!empty($item['myth']))
        <p>{{ $item['myth'] }}</p>
        @endif
        <p>Wyjaśnienie</p>
        @if (!empty($item['explanation']))
        <p>{{ $item['explanation'] }}</p>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</div>
