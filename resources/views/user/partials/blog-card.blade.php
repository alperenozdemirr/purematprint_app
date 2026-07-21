@php
  $delay = ($delay ?? 0);
@endphp
<article data-i5="reveal" data-i5-tags="blog-card reveal" class="group border-[3px] border-ink bg-surface shadow-brutal-sm overflow-hidden opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 hover:shadow-brutal" style="transition-delay:{{ $delay }}ms">
  <a href="{{ route('blogShow', $blog->slug) }}" class="block">
    <div class="aspect-[16/10] overflow-hidden border-b-[3px] border-ink">
      @if ($blog->image)
        <img src="{{ $blog->image->url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
      @else
        <div class="flex h-full w-full items-center justify-center bg-hover text-muted font-body text-sm">Görsel yok</div>
      @endif
    </div>
    <div class="p-5">
      <div class="flex flex-wrap items-center gap-2 mb-3">
        <span class="px-2 py-1 bg-badge text-badge-fg font-body text-[10px] font-semibold tracking-[0.03em] border border-action/15">{{ $blog->subtitle }}</span>
        <time class="font-body text-[11px] font-semibold text-muted uppercase tracking-[0.05em]" datetime="{{ $blog->created_at?->toDateString() }}">{{ $blog->created_at?->format('d.m.Y') }}</time>
      </div>
      <h2 class="font-heading text-card-title font-semibold leading-snug text-ink mb-2 group-hover:text-accent transition-colors">{{ $blog->title }}</h2>
      <p class="font-body text-sm text-muted leading-[1.6] m-0">{{ $blog->excerpt }}</p>
      <span class="inline-block mt-4 font-body text-xs font-bold uppercase tracking-[0.06em] text-accent">Devamını oku →</span>
    </div>
  </a>
</article>
