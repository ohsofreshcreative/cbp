<!--- values preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Dlaczego warto</div>
      <span class="acf-preview__slug">acf/values</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_values['header']))
    <p class="text-h5">{{ $g_values['header'] }}</p>
    @endif
    @if (!empty($g_values['text']))
    <div>{!! $g_values['text'] !!}</div>
    @endif

    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
      @foreach (array_slice((array) ($values ?? []), 0, 6) as $item)
      <div class="acf-preview__card flex flex-col gap-2">
        @if (!empty($item['icon']['ID']))
        <figure class="acf-preview__media m-0">
          {!! wp_get_attachment_image($item['icon']['ID'], 'thumbnail', false, ['class' => 'h-10 w-10 object-contain']) !!}
        </figure>
        @elseif (!empty($item['image']['ID']))
        <figure class="acf-preview__media m-0">
          {!! wp_get_attachment_image($item['image']['ID'], 'thumbnail', false, ['class' => 'h-14 w-full object-cover']) !!}
        </figure>
        @endif
        @if (!empty($item['header']))
        <p @class([
          'text-h6',
          'text-primary' => empty($item['icon']['ID']) && empty($item['image']['ID']),
        ])>{{ $item['header'] }}</p>
        @endif
        @if (!empty($item['opis']))
        <p>{{ $item['opis'] }}</p>
        @endif
      </div>
      @endforeach
    </div>
    @if (count((array) ($values ?? [])) > 6)
    <p>+ {{ count((array) $values) - 6 }} kolejnych wartości</p>
    @endif
  </div>
</div>
