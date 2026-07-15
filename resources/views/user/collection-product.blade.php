@extends('user.layout')
@section('title', $collection->title)
@section('content')
@php
  $label = $collection->label ?: str_pad((string) max(1, (int) $collection->number), 2, '0', STR_PAD_LEFT).' — Koleksiyon';
  $description = $collection->description ?: 'Seçilen koleksiyonun ürünleri burada listelenir.';
  $note = 'Bu koleksiyonda '.$products->total().' ürün bulunuyor.';
@endphp
<main>
    <section class="border-b-[3px] border-ink bg-surface py-10 min-[768px]:py-14">
      <div class="w-full max-w-site mx-auto px-5 lg:px-8">
        <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5" aria-label="Konum">
          <a href="{{ route('index') }}" class="transition-colors hover:text-accent">Anasayfa</a>
          <span class="opacity-[0.4]">/</span>
          <a href="{{ route('collectionList') }}" class="transition-colors hover:text-accent">Koleksiyon</a>
          <span class="opacity-[0.4]">/</span>
          <span>{{ $collection->title }}</span>
        </nav>
        <div class="grid gap-8 min-[960px]:grid-cols-[minmax(0,1fr)_320px] min-[960px]:items-end">
          <div>
            <p class="mb-3 font-body text-[11px] font-bold uppercase tracking-[0.1em] text-accent">{{ $label }}</p>
            <h1 class="font-heading text-page-title font-semibold leading-[1.1] tracking-[-0.02em]">{{ $collection->title }}</h1>
            <p class="mt-4 max-w-[64ch] text-[15px] leading-relaxed text-muted">{{ $description }}</p>
          </div>
          <div class="border-[3px] border-ink bg-bg p-5 shadow-brutal-sm">
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.1em] text-accent">Koleksiyon Notu</p>
            <p class="mt-3 text-sm leading-relaxed text-muted">{{ $note }}</p>
          </div>
        </div>
      </div>
    </section>

    <section class="py-12 pb-20 min-[768px]:py-14 min-[768px]:pb-24">
      <div class="w-full max-w-site mx-auto px-5 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 min-[768px]:flex-row min-[768px]:items-center min-[768px]:justify-between">
          <div>
            <h2 class="font-heading text-section-title font-semibold leading-[1.15]">Koleksiyon Ürünleri</h2>
            <p class="mt-2 text-sm text-muted">{{ $products->total() }} ürün · Seçilen koleksiyondaki ürünleri tek sayfada inceleyin.</p>
          </div>
          <a href="{{ route('collectionList') }}" class="inline-flex items-center justify-center border-[3px] border-ink bg-surface px-5 py-3 font-body text-[12px] font-bold uppercase tracking-[0.08em] text-ink shadow-brutal-sm transition-[transform,box-shadow] hover:-translate-x-[2px] hover:-translate-y-[2px] hover:shadow-brutal">Tüm Koleksiyonlara Dön</a>
        </div>

        <div class="grid grid-cols-2 gap-4 min-[768px]:grid-cols-3 min-[768px]:gap-5 min-[1024px]:grid-cols-4" data-i5="products">
          @forelse ($products as $product)
            @include('user.partials.product-card', ['product' => $product])
          @empty
            <div class="col-span-full border-[3px] border-ink bg-surface px-6 py-14 text-center shadow-brutal-sm">
              <p class="font-heading text-section-title font-semibold text-ink">Bu koleksiyonda ürün yok</p>
              <p class="mt-2 font-body text-[15px] text-muted">Henüz bu koleksiyona ürün eklenmemiş.</p>
              <a href="{{ route('collectionList') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover">Koleksiyonlara Dön</a>
            </div>
          @endforelse
        </div>

        @if ($products->hasPages())
          <nav class="mt-9 flex justify-center" aria-label="Ürün sayfaları" data-i5="pagination">
            {{ $products->links() }}
          </nav>
        @endif
      </div>
    </section>
  </main>
@endsection
