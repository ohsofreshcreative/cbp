<!--- cards preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Kafelki</div>
      <span class="acf-preview__slug">acf/cards</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_cards['image']['ID']))
    <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($g_cards['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
    @elseif (!empty($g_cards['image']['url']))
    <figure class="acf-preview__media m-0"><img src="{{ $g_cards['image']['url'] }}" alt="{{ $g_cards['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
    @endif
    @if (!empty($g_cards['label']))
    <p>{{ $g_cards['label'] }}</p>
    @endif
    @if (!empty($g_cards['header']))
    <p class="text-h5">{{ $g_cards['header'] }}</p>
    @endif
    @if (!empty($g_cards['text']))
    <p>{{ $g_cards['text'] }}</p>
    @endif
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($r_cards ?? []), 0, 4) as $item)
      <div class="acf-preview__card">
        @if (!empty($item['image']['ID']))
        <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($item['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
        @elseif (!empty($item['image']['url']))
        <figure class="acf-preview__media m-0"><img src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
        @endif
        @if (!empty($item['title']))
        <p class="text-h5">{{ $item['title'] }}</p>
        @endif
        @if (!empty($item['text']))
        <p>{{ $item['text'] }}</p>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</div>
