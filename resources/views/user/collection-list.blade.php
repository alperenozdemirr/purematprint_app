@extends('user.layout')
@section('title','Koleksiyonlar')
@section('content')
<main>
    <section data-i5="collection-hero" data-i5-tags="page-hero collection-hero" class="border-b-[3px] border-ink bg-surface py-10 min-[768px]:py-14">
      <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
        <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5" aria-label="Konum" data-i5="breadcrumb">
          <a href="index.html" class="transition-colors hover:text-accent">Anasayfa</a>
          <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
          <span>Koleksiyon</span>
        </nav>
        <div data-i5="reveal" data-i5-tags="section-head reveal" class="opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
          <h1 class="font-heading text-section-title font-semibold leading-[1.15] tracking-[-0.02em] normal-case" data-i5="title">Koleksiyon</h1>
          <p class="mt-3 max-w-[560px] text-[15px] leading-relaxed text-muted">Her koleksiyon, farklı bir ihtiyaca göre kürate edilmiş baskı, tabela ve marka materyallerini bir araya getirir. Projenize en uygun grubu keşfedin.</p>
        </div>
      </div>
    </section>

    <section data-i5="collection-grid" data-i5-tags="collection-grid" class="py-12 pb-20 min-[768px]:py-14 min-[768px]:pb-24">
      <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
        <div class="grid gap-6 min-[768px]:grid-cols-2 min-[1100px]:grid-cols-3" data-i5="collection__grid">
        <a data-i5-tags="collection__card" href="category.html?cat=tabela" class="group/col flex flex-col border-[3px] border-ink bg-surface shadow-brutal-sm opacity-0 translate-y-10 scale-[0.97] transition-[opacity,transform,box-shadow] duration-[600ms] ease-[cubic-bezier(0.34,1.2,0.64,1)] motion-reduce:opacity-100 motion-reduce:translate-y-0 motion-reduce:scale-100 hover:-translate-x-[3px] hover:-translate-y-[3px] hover:shadow-brutal [&.is-col-in]:translate-y-0 [&.is-col-in]:scale-100 [&.is-col-in]:opacity-100">
          <div class="relative aspect-[16/10] overflow-hidden border-b-[3px] border-ink bg-dark" data-i5="collection__media">
            <img src="{{asset('user')}}/assets/WhatsApp Image 2026-06-27 at 00.28.44.jpeg" alt="Tabela &amp; Afiş" class="h-full w-full scale-105 opacity-0 transition-[transform,opacity] duration-[750ms] ease-out group-[.is-col-in]/col:scale-100 group-[.is-col-in]/col:opacity-100 group-hover/col:scale-[1.04]">
          </div>
          <div class="flex flex-1 flex-col p-6 [&_h2]:mb-2.5 [&_h2]:font-heading [&_h2]:text-feature-title [&_h2]:font-semibold [&_h2]:leading-snug [&_h2]:normal-case [&_p]:mb-4 [&_p]:flex-1 [&_p]:text-sm [&_p]:leading-relaxed [&_p]:text-muted" data-i5="collection__body">
            <p class="mb-2 font-body text-[11px] font-bold uppercase tracking-[0.1em] text-accent" data-i5="collection__label">01 — Koleksiyon</p>
            <h2>Tabela &amp; Afiş</h2>
            <p>Lightbox, roll-up, açık hava tabelaları ve profesyonel display çözümleri.</p>
            <span class="font-body text-xs font-bold uppercase tracking-[0.06em] text-accent transition-colors group-hover/col:text-accent-dark" data-i5="collection__link">Keşfet →</span>
          </div>
        </a>

        <a data-i5-tags="collection__card" href="category.html?cat=kurumsal" class="group/col flex flex-col border-[3px] border-ink bg-surface shadow-brutal-sm opacity-0 translate-y-10 scale-[0.97] transition-[opacity,transform,box-shadow] duration-[600ms] ease-[cubic-bezier(0.34,1.2,0.64,1)] motion-reduce:opacity-100 motion-reduce:translate-y-0 motion-reduce:scale-100 hover:-translate-x-[3px] hover:-translate-y-[3px] hover:shadow-brutal [&.is-col-in]:translate-y-0 [&.is-col-in]:scale-100 [&.is-col-in]:opacity-100">
          <div class="relative aspect-[16/10] overflow-hidden border-b-[3px] border-ink bg-dark" data-i5="collection__media">
            <img src="{{asset('user')}}/assets/foto.jpeg" alt="Kurumsal Kimlik" class="h-full w-full scale-105 opacity-0 transition-[transform,opacity] duration-[750ms] ease-out group-[.is-col-in]/col:scale-100 group-[.is-col-in]/col:opacity-100 group-hover/col:scale-[1.04]">
          </div>
          <div class="flex flex-1 flex-col p-6 [&_h2]:mb-2.5 [&_h2]:font-heading [&_h2]:text-feature-title [&_h2]:font-semibold [&_h2]:leading-snug [&_h2]:normal-case [&_p]:mb-4 [&_p]:flex-1 [&_p]:text-sm [&_p]:leading-relaxed [&_p]:text-muted" data-i5="collection__body">
            <p class="mb-2 font-body text-[11px] font-bold uppercase tracking-[0.1em] text-accent" data-i5="collection__label">02 — Koleksiyon</p>
            <h2>Kurumsal Kimlik</h2>
            <p>Kartvizit, antetli kağıt, zarf, klasör ve tam kurumsal kimlik setleri.</p>
            <span class="font-body text-xs font-bold uppercase tracking-[0.06em] text-accent transition-colors group-hover/col:text-accent-dark" data-i5="collection__link">Keşfet →</span>
          </div>
        </a>

        <a data-i5-tags="collection__card" href="category.html?cat=ambalaj" class="group/col flex flex-col border-[3px] border-ink bg-surface shadow-brutal-sm opacity-0 translate-y-10 scale-[0.97] transition-[opacity,transform,box-shadow] duration-[600ms] ease-[cubic-bezier(0.34,1.2,0.64,1)] motion-reduce:opacity-100 motion-reduce:translate-y-0 motion-reduce:scale-100 hover:-translate-x-[3px] hover:-translate-y-[3px] hover:shadow-brutal [&.is-col-in]:translate-y-0 [&.is-col-in]:scale-100 [&.is-col-in]:opacity-100">
          <div class="relative aspect-[16/10] overflow-hidden border-b-[3px] border-ink bg-dark" data-i5="collection__media">
            <img src="{{asset('user')}}/assets/foto1.jpeg" alt="Ambalaj &amp; Display" class="h-full w-full scale-105 opacity-0 transition-[transform,opacity] duration-[750ms] ease-out group-[.is-col-in]/col:scale-100 group-[.is-col-in]/col:opacity-100 group-hover/col:scale-[1.04]">
          </div>
          <div class="flex flex-1 flex-col p-6 [&_h2]:mb-2.5 [&_h2]:font-heading [&_h2]:text-feature-title [&_h2]:font-semibold [&_h2]:leading-snug [&_h2]:normal-case [&_p]:mb-4 [&_p]:flex-1 [&_p]:text-sm [&_p]:leading-relaxed [&_p]:text-muted" data-i5="collection__body">
            <p class="mb-2 font-body text-[11px] font-bold uppercase tracking-[0.1em] text-accent" data-i5="collection__label">03 — Koleksiyon</p>
            <h2>Ambalaj &amp; Display</h2>
            <p>Özel kutu, kraft çanta, stand ve mağaza display ürünleri.</p>
            <span class="font-body text-xs font-bold uppercase tracking-[0.06em] text-accent transition-colors group-hover/col:text-accent-dark" data-i5="collection__link">Keşfet →</span>
          </div>
        </a>

        <a data-i5-tags="collection__card" href="category.html?cat=dijital" class="group/col flex flex-col border-[3px] border-ink bg-surface shadow-brutal-sm opacity-0 translate-y-10 scale-[0.97] transition-[opacity,transform,box-shadow] duration-[600ms] ease-[cubic-bezier(0.34,1.2,0.64,1)] motion-reduce:opacity-100 motion-reduce:translate-y-0 motion-reduce:scale-100 hover:-translate-x-[3px] hover:-translate-y-[3px] hover:shadow-brutal [&.is-col-in]:translate-y-0 [&.is-col-in]:scale-100 [&.is-col-in]:opacity-100">
          <div class="relative aspect-[16/10] overflow-hidden border-b-[3px] border-ink bg-dark" data-i5="collection__media">
            <img src="{{asset('user')}}/assets/foto2.jpeg" alt="Dijital Baskı" class="h-full w-full scale-105 opacity-0 transition-[transform,opacity] duration-[750ms] ease-out group-[.is-col-in]/col:scale-100 group-[.is-col-in]/col:opacity-100 group-hover/col:scale-[1.04]">
          </div>
          <div class="flex flex-1 flex-col p-6 [&_h2]:mb-2.5 [&_h2]:font-heading [&_h2]:text-feature-title [&_h2]:font-semibold [&_h2]:leading-snug [&_h2]:normal-case [&_p]:mb-4 [&_p]:flex-1 [&_p]:text-sm [&_p]:leading-relaxed [&_p]:text-muted" data-i5="collection__body">
            <p class="mb-2 font-body text-[11px] font-bold uppercase tracking-[0.1em] text-accent" data-i5="collection__label">04 — Koleksiyon</p>
            <h2>Dijital Baskı</h2>
            <p>El ilanı, poster, afiş ve kısa tiraj dijital baskı çözümleri.</p>
            <span class="font-body text-xs font-bold uppercase tracking-[0.06em] text-accent transition-colors group-hover/col:text-accent-dark" data-i5="collection__link">Keşfet →</span>
          </div>
        </a>

        <a data-i5-tags="collection__card" href="bestsellers.html" class="group/col flex flex-col border-[3px] border-ink bg-surface shadow-brutal-sm opacity-0 translate-y-10 scale-[0.97] transition-[opacity,transform,box-shadow] duration-[600ms] ease-[cubic-bezier(0.34,1.2,0.64,1)] motion-reduce:opacity-100 motion-reduce:translate-y-0 motion-reduce:scale-100 hover:-translate-x-[3px] hover:-translate-y-[3px] hover:shadow-brutal [&.is-col-in]:translate-y-0 [&.is-col-in]:scale-100 [&.is-col-in]:opacity-100">
          <div class="relative aspect-[16/10] overflow-hidden border-b-[3px] border-ink bg-dark" data-i5="collection__media">
            <img src="{{asset('user')}}/assets/foto5.jpeg" alt="Çok Satanlar" class="h-full w-full scale-105 opacity-0 transition-[transform,opacity] duration-[750ms] ease-out group-[.is-col-in]/col:scale-100 group-[.is-col-in]/col:opacity-100 group-hover/col:scale-[1.04]">
          </div>
          <div class="flex flex-1 flex-col p-6 [&_h2]:mb-2.5 [&_h2]:font-heading [&_h2]:text-feature-title [&_h2]:font-semibold [&_h2]:leading-snug [&_h2]:normal-case [&_p]:mb-4 [&_p]:flex-1 [&_p]:text-sm [&_p]:leading-relaxed [&_p]:text-muted" data-i5="collection__body">
            <p class="mb-2 font-body text-[11px] font-bold uppercase tracking-[0.1em] text-accent" data-i5="collection__label">Seçki</p>
            <h2>Çok Satanlar</h2>
            <p>Müşterilerimizin en çok tercih ettiği ürünlerden oluşan öne çıkan seçki.</p>
            <span class="font-body text-xs font-bold uppercase tracking-[0.06em] text-accent transition-colors group-hover/col:text-accent-dark" data-i5="collection__link">İncele →</span>
          </div>
        </a>

        <a data-i5-tags="collection__card" href="products.html" class="group/col flex flex-col border-[3px] border-ink bg-surface shadow-brutal-sm opacity-0 translate-y-10 scale-[0.97] transition-[opacity,transform,box-shadow] duration-[600ms] ease-[cubic-bezier(0.34,1.2,0.64,1)] motion-reduce:opacity-100 motion-reduce:translate-y-0 motion-reduce:scale-100 hover:-translate-x-[3px] hover:-translate-y-[3px] hover:shadow-brutal [&.is-col-in]:translate-y-0 [&.is-col-in]:scale-100 [&.is-col-in]:opacity-100">
          <div class="relative aspect-[16/10] overflow-hidden border-b-[3px] border-ink bg-dark" data-i5="collection__media">
            <img src="{{asset('user')}}/assets/foto3.jpeg" alt="Tüm Ürünler" class="h-full w-full scale-105 opacity-0 transition-[transform,opacity] duration-[750ms] ease-out group-[.is-col-in]/col:scale-100 group-[.is-col-in]/col:opacity-100 group-hover/col:scale-[1.04]">
          </div>
          <div class="flex flex-1 flex-col p-6 [&_h2]:mb-2.5 [&_h2]:font-heading [&_h2]:text-feature-title [&_h2]:font-semibold [&_h2]:leading-snug [&_h2]:normal-case [&_p]:mb-4 [&_p]:flex-1 [&_p]:text-sm [&_p]:leading-relaxed [&_p]:text-muted" data-i5="collection__body">
            <p class="mb-2 font-body text-[11px] font-bold uppercase tracking-[0.1em] text-accent" data-i5="collection__label">Tümü</p>
            <h2>Tüm Ürünler</h2>
            <p>Katalogdaki tüm baskı, tabela ve marka materyallerini görüntüleyin.</p>
            <span class="font-body text-xs font-bold uppercase tracking-[0.06em] text-accent transition-colors group-hover/col:text-accent-dark" data-i5="collection__link">Mağazaya Git →</span>
          </div>
        </a>
        </div>
      </div>
    </section>
  </main>
@endsection