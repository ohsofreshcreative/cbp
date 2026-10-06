<!--- contact preview --->

<div class="acf-preview" @if(!empty($nomt)) data-acf-nomt @endif>
  <div class="acf-preview__meta">
    <div class="acf-preview__heading">
      <div class="acf-preview__title">Kontakt</div>
      <span class="acf-preview__slug">acf/contact</span>
    </div>
    @include('partials.block-preview-settings')
  </div>
  <div class="acf-preview__content">
    @if (!empty($g_contact_1['image']['ID']))
    <figure class="acf-preview__media m-0">{!! wp_get_attachment_image($g_contact_1['image']['ID'], 'thumbnail', false, ['class' => 'h-20 w-32 object-contain']) !!}</figure>
    @elseif (!empty($g_contact_1['image']['url']))
    <figure class="acf-preview__media m-0"><img src="{{ $g_contact_1['image']['url'] }}" alt="{{ $g_contact_1['image']['alt'] ?? '' }}" class="h-20 w-32 object-contain"></figure>
    @endif
    @if (!empty($g_contact_1['header']))
    <p class="text-h5">{{ $g_contact_1['header'] }}</p>
    @endif
    @if (!empty($g_contact_1['text']))
    <div>{!! $g_contact_1['text'] !!}</div>
    @endif
    @if (!empty($g_contact_1['phone']))
    <p>{{ $g_contact_1['phone'] }}</p>
    @endif
    @if (!empty($g_contact_1['mail']))
    <p>{{ $g_contact_1['mail'] }}</p>
    @endif
    @if (!empty($g_contact_1['address']))
    <div>{!! $g_contact_1['address'] !!}</div>
    @endif
    <div class="acf-preview__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      @foreach (array_slice((array) ($g_contact_1['benefits'] ?? []), 0, 4) as $item)
      <div class="acf-preview__card">
        @if (!empty($item['text']))
        <p>{{ $item['text'] }}</p>
        @endif
      </div>
      @endforeach
    </div>
    @if (!empty($g_contact_2['title']))
    <p>{{ $g_contact_2['title'] }}</p>
    @endif
    @if (!empty($g_contact_2['shortcode']))
    <p>Formularz kontaktowy: {{ $g_contact_2['shortcode'] }}</p>
    @endif
  </div>
</div>
