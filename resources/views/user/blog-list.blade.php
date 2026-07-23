@extends('user.layout')
@section('title','Bloglar')
@section('content')
<main class="pt-8 pb-20">
  <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
    <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-6 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
      <a href="{{ route('index') }}">Anasayfa</a>
      <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
      <span>Blog</span>
    </nav>

    <header class="mb-10 border-[3px] border-ink bg-surface p-6 md:p-8 shadow-brutal-sm" data-i5="reveal" data-i5-tags="blog-page__head reveal">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.12em] text-accent mb-3">İçerik & Rehberler</p>
      <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] normal-case">Blog</h1>
      <p class="mt-3 text-[15px] text-muted leading-[1.65] max-w-[620px]">Baskı dünyasından ipuçları, ürün rehberleri ve proje hikayeleri. Markanızı bir adım öne taşıyacak içerikler.</p>
    </header>

    @if ($blogs->count())
      <div class="grid gap-6 min-[640px]:grid-cols-2 min-[1024px]:grid-cols-3" data-i5="blog-grid">
        @foreach ($blogs as $blog)
          @include('user.partials.blog-card', ['blog' => $blog, 'delay' => $loop->index * 60])
        @endforeach
      </div>

      @if ($blogs->hasPages())
        <div class="mt-12 flex justify-center">
          {{ $blogs->links() }}
        </div>
      @endif
    @else
      <div class="border-[3px] border-ink bg-surface shadow-brutal-sm p-10 text-center">
        <h2 class="font-heading text-xl font-semibold mb-2">Henüz blog yazısı yok</h2>
        <p class="text-muted mb-6">Yakında yeni içerikler eklenecek.</p>
        <a href="{{ route('shops') }}" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover">Alışverişe Başla</a>
      </div>
    @endif
  </div>
</main>
@endsection
