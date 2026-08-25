@extends('admin.layout')
@section('title', 'Yeni Demo Yorum')
@section('page_title', 'Yeni Demo Yorum')
@section('breadcrumb', 'İçerik / Demo Yorumlar / Yeni')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.homepageDemoReviewList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Yeni Demo Yorum</h2>
      <p class="font-body text-[13px] text-muted">Anasayfa müşteri yorumları bölümü için</p>
    </div>
  </div>

  <form action="{{ route('admin.homepageDemoReviewStore') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.partials.homepage-demo-review-form')
  </form>
@endsection
