@extends('user.layout')
@section('title','Bloglar')
@section('content')
<main>
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Blog</span>
      </nav>

      <div data-i5="reveal" data-i5-tags="blog-page__head reveal" class="mb-10 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case">
        <h1>Blog</h1>
        <p class="mt-2.5 text-[15px] text-muted leading-[1.6] max-w-[560px]">Baskı dünyasından ipuçları, ürün rehberleri ve proje hikayeleri. Markanızı bir adım öne taşıyacak içerikler.</p>
      </div>

      @if ($blogs->count())
        <div class="grid gap-6 min-[640px]:grid-cols-2 min-[1024px]:grid-cols-3" data-i5="blog-grid">
          @foreach ($blogs as $blog)
            @include('user.partials.blog-card', ['blog' => $blog, 'delay' => $loop->index * 60])
          @endforeach
        </div>

        @if ($blogs->hasPages())
          <div class="mt-10">
            {{ $blogs->links() }}
          </div>
        @endif
      @else
        <div class="p-8 border-[3px] border-ink bg-surface shadow-brutal-sm text-center">
          <h2 class="font-heading text-xl font-semibold mb-2">Henüz blog yazısı yok</h2>
          <p class="text-muted">Yakında yeni içerikler eklenecek.</p>
        </div>
      @endif
    </div>
  </main>
@endsection
