<!--- proces preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Proces</div>
      <span class="acf-preview__slug">acf/proces</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_proces['header']))
    <p class="text-h5">{{ $g_proces['header'] }}</p>
    @endif
    @if (!empty($g_proces['txt']))
    <div>{!! $g_proces['txt'] !!}</div>
    @endif

    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($r_proces ?? []), 0, 4) as $item)
      <div class="acf-preview__card flex flex-col gap-2">
        @if (!empty($item['image']['ID']))
        <figure class="acf-preview__media m-0">
          {!! wp_get_attachment_image($item['image']['ID'], 'thumbnail', false, ['class' => 'h-12 w-12 object-contain']) !!}
        </figure>
        @endif
        <span class="acf-preview-button">{{ !empty($item['number']) ? $item['number'] : $loop->iteration }}</span>
        @if (!empty($item['title']))
        <p class="text-h6">{{ $item['title'] }}</p>
        @endif
        @if (!empty($item['txt']))
        <div>{!! $item['txt'] !!}</div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</div>
