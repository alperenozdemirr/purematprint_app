@extends('user.layout')
@section('title', $blog->title)
@section('content')
<main class="pt-8 pb-20">
  <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
    <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-6 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
      <a href="{{ route('index') }}">Anasayfa</a>
      <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
      <a href="{{ route('blogList') }}">Blog</a>
      <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
      <span class="line-clamp-1">{{ $blog->title }}</span>
    </nav>

    <article id="blog-article" class="border-[3px] border-ink bg-surface shadow-brutal-sm overflow-hidden" data-i5="blog-article">
      @if ($blog->image)
        <div class="border-b-[3px] border-ink aspect-[16/9] min-[768px]:aspect-[21/9]">
          <img src="{{ $blog->image->url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
        </div>
      @endif

      <div class="p-6 md:p-8 lg:p-10">
        <header class="mb-8 pb-8 border-b-[3px] border-ink">
          <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="px-2.5 py-1 bg-badge text-badge-fg font-body text-[11px] font-semibold tracking-[0.03em] border border-action/15">{{ $blog->subtitle }}</span>
            <time class="font-body text-xs font-semibold text-muted uppercase tracking-[0.06em]" datetime="{{ $blog->created_at?->toDateString() }}">{{ $blog->created_at?->format('d.m.Y') }}</time>
          </div>
          <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] normal-case mb-4">{{ $blog->title }}</h1>
          <p class="text-[15px] text-muted leading-[1.65] max-w-[65ch]">{{ $blog->excerpt }}</p>
        </header>

        <div class="blog-prose max-w-[68ch] font-body text-[15px] leading-[1.8] text-ink [&_h2]:font-heading [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:mt-9 [&_h2]:mb-3 [&_h2]:normal-case [&_p]:mb-4 [&_p]:text-muted [&_ul]:my-4 [&_ul]:pl-5 [&_ul]:list-disc [&_ul]:text-muted [&_ol]:my-4 [&_ol]:pl-5 [&_ol]:list-decimal [&_ol]:text-muted [&_li]:mb-2 [&_strong]:text-ink [&_img]:max-w-full [&_img]:h-auto [&_img]:border-[3px] [&_img]:border-ink [&_a]:text-accent [&_a]:underline [&_a]:underline-offset-[3px]">
          {!! $blog->content !!}
        </div>

        <div class="mt-10 pt-6 border-t border-ink/15">
          <a href="{{ route('blogList') }}" class="inline-flex items-center gap-2 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-accent hover:text-accent-dark transition-colors">← Tüm Yazılar</a>
        </div>
      </div>
    </article>

    @if ($relatedBlogs->isNotEmpty())
      <section class="mt-12 pt-10 border-t-[3px] border-ink" data-i5="blog-related-section">
        <div class="mb-8 flex flex-col gap-2 min-[640px]:flex-row min-[640px]:items-end min-[640px]:justify-between">
          <h2 class="font-heading text-section-title font-semibold leading-[1.15] tracking-[-0.02em] normal-case">Diğer Yazılar</h2>
          <a href="{{ route('blogList') }}" class="font-body text-[13px] font-bold uppercase tracking-[0.06em] text-accent hover:text-accent-dark">Tümünü Gör</a>
        </div>
        <div class="grid gap-6 min-[640px]:grid-cols-2 min-[1024px]:grid-cols-3" id="blog-related" data-i5="blog-related">
          @foreach ($relatedBlogs as $relatedBlog)
            @include('user.partials.blog-card', ['blog' => $relatedBlog, 'delay' => 0])
          @endforeach
        </div>
      </section>
    @endif
  </div>
</main>
@endsection
