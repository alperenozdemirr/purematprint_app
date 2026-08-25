@extends('admin.layout')
@section('title', 'Demo Yorum Düzenle')
@section('page_title', 'Demo Yorum Düzenle')
@section('breadcrumb', 'İçerik / Demo Yorumlar / Düzenle')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.homepageDemoReviewList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Demo Yorum Düzenle</h2>
      <p class="font-body text-[13px] text-muted">{{ $review->author }}</p>
    </div>
  </div>

  @if (session('success'))
    <div class="mb-5 rounded-xl border border-success/20 bg-success/5 px-4 py-3">
      <p class="text-sm font-medium text-success">{{ session('success') }}</p>
    </div>
  @endif

  <form action="{{ route('admin.homepageDemoReviewUpdate') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $review->id }}">
    @include('admin.partials.homepage-demo-review-form', ['review' => $review])
  </form>
@endsection
