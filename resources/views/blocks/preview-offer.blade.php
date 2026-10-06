<!--- offer preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Obszary oferty</div>
      <span class="acf-preview__slug">acf/offer</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_offer['header']))
    <p class="text-h5">{{ $g_offer['header'] }}</p>
    @endif
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($categories ?? []), 0, 4) as $item)
      <div class="acf-preview__card">
        @if (!empty($item['image']['ID']))
        <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($item['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
        @elseif (!empty($item['image']['url']))
        <figure class="acf-preview__media m-0"><img src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
        @endif
        @if (!empty($item['name']))
        <p class="text-h5">{{ $item['name'] }}</p>
        @endif
        @if (!empty($item['description']))
        <div>{!! $item['description'] !!}</div>
        @endif
        @foreach (($item['posts'] ?? []) as $offerPost)
        <p>{{ get_the_title($offerPost) }}</p>
        @endforeach
      </div>
      @endforeach
    </div>
    @if (empty($categories))
    <p>Dodaj kategorie i przypisz do nich opublikowane wpisy w sekcji Oferta.</p>
    @endif
  </div>
</div>
