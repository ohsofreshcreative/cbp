<!--- reviews preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Opinie</div>
      <span class="acf-preview__slug">acf/reviews</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($header))
    <p class="text-h5">{{ $header }}</p>
    @endif
    @if (is_numeric($reviews_rating ?? null))
    <p>Średnia ocen: {{ number_format(max(0, min(5, (float) $reviews_rating)), 1, ',', '') }} / 5</p>
    @endif
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($r_reviews ?? []), 0, 4) as $item)
      <div class="acf-preview__card">
        @if (!empty($item['image']['ID']))
        <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($item['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
        @elseif (!empty($item['image']['url']))
        <figure class="acf-preview__media m-0"><img src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
        @endif
        @if (!empty($item['name']))
        <p class="text-h5">{{ $item['name'] }}</p>
        @endif
        @if (!empty($item['position']))
        <p>{{ $item['position'] }}</p>
        @endif
        @if (!empty($item['header']))
        <p>{{ $item['header'] }}</p>
        @endif
        @if (!empty($item['txt']))
        <div>{!! $item['txt'] !!}</div>
        @endif
      </div>
      @endforeach
    </div>
    <p>Treści edytujemy w menu „Opinie”.</p>
  </div>
</div>
