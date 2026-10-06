<!--- offers preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Oferta</div>
      <span class="acf-preview__slug">acf/offers</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_offers['header']))
    <p class="text-h5">{{ $g_offers['header'] }}</p>
    @endif
    @if (!empty($g_offers['button']['title']))
    <div class="acf-preview-actions"><span class="acf-preview-button">{{ $g_offers['button']['title'] }}</span></div>
    @endif
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($offer_items ?? []), 0, 4) as $item)
      <div class="acf-preview__card flex flex-col gap-2">
        @if (!empty($item['image_url']))
        <figure class="acf-preview__media m-0"><img class="h-16 w-24 object-cover" src="{{ $item['image_url'] }}" alt="{{ $item['image_alt'] ?? '' }}"></figure>
        @endif
        <p class="text-h6">{{ $item['title'] }}</p>
        @if (!empty($item['excerpt']))
        <p>{{ $item['excerpt'] }}</p>
        @endif
        <span class="acf-preview-button">Sprawdź</span>
      </div>
      @endforeach
    </div>
    @if (empty($offer_items))
    <p>Oferty są pobierane automatycznie z wpisów typu Oferta.</p>
    @endif
  </div>
</div>
