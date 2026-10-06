<!--- certificates preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Certyfikaty</div>
      <span class="acf-preview__slug">acf/certificates</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_certificates['header']))
    <p class="text-h5">{{ $g_certificates['header'] }}</p>
    @endif
    @if (!empty($g_certificates['text']))
    <p>{{ $g_certificates['text'] }}</p>
    @endif
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($g_certificates['gallery'] ?? []), 0, 4) as $item)
      <div class="acf-preview__card">
        @if (!empty($item['ID']))
        <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($item['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
        @elseif (!empty($item['url']))
        <figure class="acf-preview__media m-0"><img src="{{ $item['url'] }}" alt="{{ $item['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
        @endif
      </div>
      @endforeach
    </div>
    <p>Treści i zdjęcia edytujemy w menu „Certyfikaty”.</p>
  </div>
</div>
