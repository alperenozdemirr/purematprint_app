@extends('user.layout')
@section('title','Siparişlerim')
@section('content')
<main id="orders-root" class="pt-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="index.html">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Siparişlerim</span>
      </nav>

      <div class="mb-8 pt-2 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="orders-page__head">
        <h1>Siparişlerim</h1>
        <p class="mt-2.5 text-sm text-muted font-semibold" data-i5="orders-page__sub">demo@purematprint.com hesabına ait siparişler</p>
      </div>

      <div class="flex flex-wrap gap-2 mb-7 border-[3px] border-ink shadow-brutal-sm bg-surface p-2" role="tablist" aria-label="Sipariş durumu" data-i5="orders-filters">
        <button type="button" class="px-4 py-2.5 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&.is-active]:bg-action [&.is-active]:text-on-dark is-active" role="tab" data-filter="all" aria-selected="true" data-i5="orders-filter">Tümü</button>
        <button type="button" class="px-4 py-2.5 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&.is-active]:bg-action [&.is-active]:text-on-dark" role="tab" data-filter="active" aria-selected="false" data-i5="orders-filter">Devam Eden</button>
        <button type="button" class="px-4 py-2.5 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&.is-active]:bg-action [&.is-active]:text-on-dark" role="tab" data-filter="delivered" aria-selected="false" data-i5="orders-filter">Teslim Edilen</button>
        <button type="button" class="px-4 py-2.5 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&.is-active]:bg-action [&.is-active]:text-on-dark" role="tab" data-filter="cancelled" aria-selected="false" data-i5="orders-filter">İptal</button>
      </div>

      <div class="py-16 px-6 text-center border-[3px] border-ink shadow-brutal bg-surface hidden [&_h2]:font-body [&_h2]:text-xl [&_h2]:font-bold [&_h2]:uppercase [&_h2]:mb-2 [&_p]:text-muted [&_p]:mb-6" id="orders-empty" data-i5="orders-empty">
        <h2>Bu filtrede sipariş yok</h2>
        <p>Seçtiğiniz durumda görüntülenecek sipariş bulunamadı.</p>
        <a data-i5="btn--fill" data-i5-tags="btn btn--fill" href="products.html" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">Alışverişe Başla</a>
      </div>

      <div class="grid gap-4" id="orders-list" data-i5="orders-list">
        <article class="border-[3px] border-ink shadow-brutal-sm bg-surface transition-shadow hover:shadow-brutal [&.is-filter-hidden]:hidden" data-order-status="pending" data-i5="order-card">
          <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b-[3px] border-ink bg-bg" data-i5="order-card__head">
            <div>
              <p class="font-body text-[13px] font-bold uppercase tracking-[0.02em]" data-i5="order-card__id">PMP-DEMO005</p>
              <p class="text-[13px] text-muted" data-i5="order-card__date">7 Temmuz 2026 10:45</p>
            </div>
            <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2.5 py-[5px] border-2 border-ink [&.is-pending]:bg-[#fff8e6] [&.is-pending]:border-[#d97706] [&.is-pending]:text-[#92400e] [&.is-processing]:bg-accent/10 [&.is-processing]:border-accent [&.is-processing]:text-accent [&.is-shipped]:bg-accent/15 [&.is-shipped]:border-accent [&.is-shipped]:text-accent-dark [&.is-delivered]:bg-[rgba(21,128,61,0.1)] [&.is-delivered]:border-[#15803d] [&.is-delivered]:text-[#15803d] [&.is-cancelled]:bg-[rgba(182,29,15,0.08)] [&.is-cancelled]:border-announce [&.is-cancelled]:text-announce is-pending" data-i5="order-card__status">Yeni Sipariş</span>
          </div>
          <div class="grid gap-4 p-5 min-[640px]:grid-cols-[1fr_auto] min-[640px]:items-center" data-i5="order-card__body">
            <div class="flex gap-2 flex-wrap" data-i5="order-card__items">
              <div class="w-14 h-14 border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg shrink-0 [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="order-card__thumb">
                <img src="assets/foto5.jpeg" alt="Premium Kartvizit">
              </div>
              <div class="flex-1 min-w-0 [&_p]:text-sm [&_p]:font-semibold [&_p]:mb-1 [&_span]:text-[13px] [&_span]:text-muted" data-i5="order-card__info">
                <p>Premium Kartvizit</p>
                <span>2 adet · Kredi / Banka Kartı</span>
              </div>
            </div>
            <div class="text-right" data-i5="order-card__side">
              <p class="font-body text-lg font-bold mb-2.5" data-i5="order-card__total">1.120 ₺</p>
              <div class="flex flex-wrap gap-2 justify-end" data-i5="order-card__actions">
                <a data-i5="order-card__btn--primary" data-i5-tags="order-card__btn order-card__btn--primary" href="order-detail.html?id=PMP-DEMO005" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm transition-colors bg-accent text-on-dark border-accent hover:bg-action hover:border-ink">Detay</a>
                <a href="order-success.html?order=PMP-DEMO005" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface text-ink transition-colors hover:bg-hover" data-i5="order-card__btn">Özet</a>
                <a href="order-cancel.html?id=PMP-DEMO005" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface text-announce transition-colors hover:bg-[rgba(182,29,15,0.08)]" data-i5="order-card__btn--cancel">İptal Et</a>
              </div>
            </div>
          </div>
        </article>

        <article class="border-[3px] border-ink shadow-brutal-sm bg-surface transition-shadow hover:shadow-brutal [&.is-filter-hidden]:hidden" data-order-status="delivered" data-i5="order-card">
          <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b-[3px] border-ink bg-bg" data-i5="order-card__head">
            <div>
              <p class="font-body text-[13px] font-bold uppercase tracking-[0.02em]" data-i5="order-card__id">PMP-DEMO001</p>
              <p class="text-[13px] text-muted" data-i5="order-card__date">19 Mayıs 2026 14:30</p>
            </div>
            <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2.5 py-[5px] border-2 border-ink [&.is-pending]:bg-[#fff8e6] [&.is-pending]:border-[#d97706] [&.is-pending]:text-[#92400e] [&.is-processing]:bg-accent/10 [&.is-processing]:border-accent [&.is-processing]:text-accent [&.is-shipped]:bg-accent/15 [&.is-shipped]:border-accent [&.is-shipped]:text-accent-dark [&.is-delivered]:bg-[rgba(21,128,61,0.1)] [&.is-delivered]:border-[#15803d] [&.is-delivered]:text-[#15803d] [&.is-cancelled]:bg-[rgba(182,29,15,0.08)] [&.is-cancelled]:border-announce [&.is-cancelled]:text-announce is-pending" data-i5="order-card__status">Teslim Edildi</span>
          </div>
          <div class="grid gap-4 p-5 min-[640px]:grid-cols-[1fr_auto] min-[640px]:items-center" data-i5="order-card__body">
            <div class="flex gap-2 flex-wrap" data-i5="order-card__items">
              <div class="w-14 h-14 border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg shrink-0 [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="order-card__thumb">
                <img src="assets/foto5.jpeg" alt="Premium Kartvizit">
              </div>
              <div class="w-14 h-14 border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg shrink-0 [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="order-card__thumb">
                <img src="assets/WhatsApp Image 2026-06-27 at 00.28.43.jpeg" alt="Magnet Afiş Seti">
              </div>
              <div class="flex-1 min-w-0 [&_p]:text-sm [&_p]:font-semibold [&_p]:mb-1 [&_span]:text-[13px] [&_span]:text-muted" data-i5="order-card__info">
                <p>Premium Kartvizit +1 ürün</p>
                <span>2 adet · Kredi / Banka Kartı</span>
              </div>
            </div>
            <div class="text-right" data-i5="order-card__side">
              <p class="font-body text-lg font-bold mb-2.5" data-i5="order-card__total">1.240 ₺</p>
              <div class="flex flex-wrap gap-2 justify-end" data-i5="order-card__actions">
                <a data-i5="order-card__btn--primary" data-i5-tags="order-card__btn order-card__btn--primary" href="order-detail.html?id=PMP-DEMO001" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm transition-colors bg-accent text-on-dark border-accent hover:bg-action hover:border-ink">Detay</a>
                <a href="order-success.html?order=PMP-DEMO001" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface transition-colors hover:bg-hover" data-i5="order-card__btn">Özet</a>
              </div>
            </div>
          </div>
        </article>

        <article class="border-[3px] border-ink shadow-brutal-sm bg-surface transition-shadow hover:shadow-brutal [&.is-filter-hidden]:hidden" data-order-status="processing" data-i5="order-card">
          <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b-[3px] border-ink bg-bg" data-i5="order-card__head">
            <div>
              <p class="font-body text-[13px] font-bold uppercase tracking-[0.02em]" data-i5="order-card__id">PMP-DEMO002</p>
              <p class="text-[13px] text-muted" data-i5="order-card__date">31 Mayıs 2026 10:15</p>
            </div>
            <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2.5 py-[5px] border-2 border-ink [&.is-pending]:bg-[#fff8e6] [&.is-pending]:border-[#d97706] [&.is-pending]:text-[#92400e] [&.is-processing]:bg-accent/10 [&.is-processing]:border-accent [&.is-processing]:text-accent [&.is-shipped]:bg-accent/15 [&.is-shipped]:border-accent [&.is-shipped]:text-accent-dark [&.is-delivered]:bg-[rgba(21,128,61,0.1)] [&.is-delivered]:border-[#15803d] [&.is-delivered]:text-[#15803d] [&.is-cancelled]:bg-[rgba(182,29,15,0.08)] [&.is-cancelled]:border-announce [&.is-cancelled]:text-announce is-pending" data-i5="order-card__status">Üretimde</span>
          </div>
          <div class="grid gap-4 p-5 min-[640px]:grid-cols-[1fr_auto] min-[640px]:items-center" data-i5="order-card__body">
            <div class="flex gap-2 flex-wrap" data-i5="order-card__items">
              <div class="w-14 h-14 border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg shrink-0 [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="order-card__thumb">
                <img src="assets/foto1.jpeg" alt="Roll-Up Banner">
              </div>
              <div class="flex-1 min-w-0 [&_p]:text-sm [&_p]:font-semibold [&_p]:mb-1 [&_span]:text-[13px] [&_span]:text-muted" data-i5="order-card__info">
                <p>Roll-Up Banner</p>
                <span>1 adet · Havale / EFT</span>
              </div>
            </div>
            <div class="text-right" data-i5="order-card__side">
              <p class="font-body text-lg font-bold mb-2.5" data-i5="order-card__total">979 ₺</p>
              <div class="flex flex-wrap gap-2 justify-end" data-i5="order-card__actions">
                <a data-i5="order-card__btn--primary" data-i5-tags="order-card__btn order-card__btn--primary" href="order-detail.html?id=PMP-DEMO002" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm transition-colors bg-accent text-on-dark border-accent hover:bg-action hover:border-ink">Detay</a>
                <a href="order-success.html?order=PMP-DEMO002" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface text-ink transition-colors hover:bg-hover" data-i5="order-card__btn">Özet</a>
                <a href="order-cancel.html?id=PMP-DEMO002" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface text-announce transition-colors hover:bg-[rgba(182,29,15,0.08)]" data-i5="order-card__btn--cancel">İptal Et</a>
              </div>
            </div>
          </div>
        </article>

        <article class="border-[3px] border-ink shadow-brutal-sm bg-surface transition-shadow hover:shadow-brutal [&.is-filter-hidden]:hidden" data-order-status="shipped" data-i5="order-card">
          <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b-[3px] border-ink bg-bg" data-i5="order-card__head">
            <div>
              <p class="font-body text-[13px] font-bold uppercase tracking-[0.02em]" data-i5="order-card__id">PMP-DEMO003</p>
              <p class="text-[13px] text-muted" data-i5="order-card__date">2 Haziran 2026 16:45</p>
            </div>
            <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2.5 py-[5px] border-2 border-ink [&.is-pending]:bg-[#fff8e6] [&.is-pending]:border-[#d97706] [&.is-pending]:text-[#92400e] [&.is-processing]:bg-accent/10 [&.is-processing]:border-accent [&.is-processing]:text-accent [&.is-shipped]:bg-accent/15 [&.is-shipped]:border-accent [&.is-shipped]:text-accent-dark [&.is-delivered]:bg-[rgba(21,128,61,0.1)] [&.is-delivered]:border-[#15803d] [&.is-delivered]:text-[#15803d] [&.is-cancelled]:bg-[rgba(182,29,15,0.08)] [&.is-cancelled]:border-announce [&.is-cancelled]:text-announce is-pending" data-i5="order-card__status">Kargoda</span>
          </div>
          <div class="grid gap-4 p-5 min-[640px]:grid-cols-[1fr_auto] min-[640px]:items-center" data-i5="order-card__body">
            <div class="flex gap-2 flex-wrap" data-i5="order-card__items">
              <div class="w-14 h-14 border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg shrink-0 [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="order-card__thumb">
                <img src="assets/foto3.jpeg" alt="LED Lightbox Tabela">
              </div>
              <div class="flex-1 min-w-0 [&_p]:text-sm [&_p]:font-semibold [&_p]:mb-1 [&_span]:text-[13px] [&_span]:text-muted" data-i5="order-card__info">
                <p>LED Lightbox Tabela</p>
                <span>1 adet · Kredi / Banka Kartı</span>
              </div>
            </div>
            <div class="text-right" data-i5="order-card__side">
              <p class="font-body text-lg font-bold mb-2.5" data-i5="order-card__total">3.450 ₺</p>
              <div class="flex flex-wrap gap-2 justify-end" data-i5="order-card__actions">
                <a data-i5="order-card__btn--primary" data-i5-tags="order-card__btn order-card__btn--primary" href="order-detail.html?id=PMP-DEMO003" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm transition-colors bg-accent text-on-dark border-accent hover:bg-action hover:border-ink">Detay</a>
                <a href="order-success.html?order=PMP-DEMO003" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface transition-colors hover:bg-hover" data-i5="order-card__btn">Özet</a>
              </div>
            </div>
          </div>
        </article>

        <article class="border-[3px] border-ink shadow-brutal-sm bg-surface transition-shadow hover:shadow-brutal [&.is-filter-hidden]:hidden" data-order-status="cancelled" data-i5="order-card">
          <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b-[3px] border-ink bg-bg" data-i5="order-card__head">
            <div>
              <p class="font-body text-[13px] font-bold uppercase tracking-[0.02em]" data-i5="order-card__id">PMP-DEMO004</p>
              <p class="text-[13px] text-muted" data-i5="order-card__date">28 Mayıs 2026 09:20</p>
            </div>
            <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2.5 py-[5px] border-2 border-ink [&.is-pending]:bg-[#fff8e6] [&.is-pending]:border-[#d97706] [&.is-pending]:text-[#92400e] [&.is-processing]:bg-accent/10 [&.is-processing]:border-accent [&.is-processing]:text-accent [&.is-shipped]:bg-accent/15 [&.is-shipped]:border-accent [&.is-shipped]:text-accent-dark [&.is-delivered]:bg-[rgba(21,128,61,0.1)] [&.is-delivered]:border-[#15803d] [&.is-delivered]:text-[#15803d] [&.is-cancelled]:bg-[rgba(182,29,15,0.08)] [&.is-cancelled]:border-announce [&.is-cancelled]:text-announce is-pending" data-i5="order-card__status">İptal Edildi</span>
          </div>
          <div class="grid gap-4 p-5 min-[640px]:grid-cols-[1fr_auto] min-[640px]:items-center" data-i5="order-card__body">
            <div class="flex gap-2 flex-wrap" data-i5="order-card__items">
              <div class="w-14 h-14 border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg shrink-0 [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="order-card__thumb">
                <img src="assets/foto2.jpeg" alt="A-Frame Tabela">
              </div>
              <div class="flex-1 min-w-0 [&_p]:text-sm [&_p]:font-semibold [&_p]:mb-1 [&_span]:text-[13px] [&_span]:text-muted" data-i5="order-card__info">
                <p>A-Frame Tabela</p>
                <span>1 adet · Havale / EFT</span>
              </div>
            </div>
            <div class="text-right" data-i5="order-card__side">
              <p class="font-body text-lg font-bold mb-2.5" data-i5="order-card__total">1.120 ₺</p>
              <div class="flex flex-wrap gap-2 justify-end" data-i5="order-card__actions">
                <a data-i5="order-card__btn--primary" data-i5-tags="order-card__btn order-card__btn--primary" href="order-detail.html?id=PMP-DEMO004" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm transition-colors bg-accent text-on-dark border-accent hover:bg-action hover:border-ink">Detay</a>
                <a href="order-success.html?order=PMP-DEMO004" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface transition-colors hover:bg-hover" data-i5="order-card__btn">Özet</a>
              </div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </main>
@endsection