<!--- faq preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Najczęściej zadawane pytania</div>
      <span class="acf-preview__slug">acf/faq</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_faq['label']))
    <p>{{ $g_faq['label'] }}</p>
    @endif
    @if (!empty($g_faq['header']))
    <p class="text-h5">{{ $g_faq['header'] }}</p>
    @endif
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($r_faq ?? []), 0, 4) as $item)
      <div class="acf-preview__card">
        @if (!empty($item['title']))
        <p class="text-h5">{{ $item['title'] }}</p>
        @endif
        @if (!empty($item['txt']))
        <div>{!! $item['txt'] !!}</div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</div>
