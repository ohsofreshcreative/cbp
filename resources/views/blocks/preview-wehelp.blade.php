<!--- wehelp preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">W czym pomagamy</div>
      <span class="acf-preview__slug">acf/wehelp</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_wehelp['image']['ID']))
    <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($g_wehelp['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
    @elseif (!empty($g_wehelp['image']['url']))
    <figure class="acf-preview__media m-0"><img src="{{ $g_wehelp['image']['url'] }}" alt="{{ $g_wehelp['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
    @endif
    @if (!empty($g_wehelp['label']))
    <p>{{ $g_wehelp['label'] }}</p>
    @endif
    @if (!empty($g_wehelp['header']))
    <p class="text-h5">{{ $g_wehelp['header'] }}</p>
    @endif
    @if (!empty($g_wehelp['text']))
    <div>{!! $g_wehelp['text'] !!}</div>
    @endif
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($r_wehelp ?? []), 0, 4) as $item)
      <div class="acf-preview__card">
        @if (!empty($item['image']['ID']))
        <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($item['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
        @elseif (!empty($item['image']['url']))
        <figure class="acf-preview__media m-0"><img src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
        @endif
        @if (!empty($item['header']))
        <p class="text-h5">{{ $item['header'] }}</p>
        @endif
        @if (!empty($item['text']))
        <div>{!! $item['text'] !!}</div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</div>
