@extends('admin.layout')
@section('title', 'Yeni Soru')
@section('page_title', 'Yeni Soru')
@section('breadcrumb', 'İçerik / SSS / Yeni Soru')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ $selectedGroupId ? route('admin.faqGroupEditPage', $selectedGroupId) : route('admin.faqGroupList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Yeni Soru Ekle</h2>
      <p class="font-body text-[13px] text-muted">Soru, cevap ve anasayfa görünürlüğünü ayarlayın</p>
    </div>
  </div>

  @include('admin.partials.faq-form', [
    'action' => route('admin.faqStore'),
    'groupOptions' => $groupOptions,
    'selectedGroupId' => old('group_id', $selectedGroupId ?: null),
    'submitLabel' => 'Soruyu Kaydet',
    'cancelUrl' => $selectedGroupId ? route('admin.faqGroupEditPage', $selectedGroupId) : route('admin.faqGroupList'),
  ])
@endsection
