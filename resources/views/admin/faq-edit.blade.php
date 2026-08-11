@extends('admin.layout')
@section('title', 'Soru Düzenle')
@section('page_title', 'Soru Düzenle')
@section('breadcrumb', 'İçerik / SSS / Soru Düzenle')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.faqGroupEditPage', $faq->group_id) }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Soru Düzenle</h2>
      <p class="font-body text-[13px] text-muted">{{ $faq->group?->title }}</p>
    </div>
  </div>

  @include('admin.partials.faq-form', [
    'action' => route('admin.faqUpdate'),
    'faq' => $faq,
    'groupOptions' => $groupOptions,
    'selectedGroupId' => old('group_id', $faq->group_id),
    'submitLabel' => 'Soruyu Güncelle',
    'cancelUrl' => route('admin.faqGroupEditPage', $faq->group_id),
  ])
@endsection
