@extends('user.layout')
@section('title','Sipariş detay')
@section('content')
 <main id="order-detail-root" class="pt-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="index.html">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <a href="orders.html">Siparişlerim</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>PMP-DEMO001</span>
      </nav>

      <div class="flex flex-wrap items-start justify-between gap-4 mb-5 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="order-detail__header">
        <div>
          <h1>PMP-DEMO001</h1>
          <p class="text-[13px] text-muted mt-1.5" data-i5="order-detail__meta">19 Mayıs 2026 14:30 · 2 ürün · Kredi / Banka Kartı</p>
        </div>
        <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2.5 py-[5px] border-2 border-ink [&.is-pending]:bg-[#fff8e6] [&.is-pending]:border-[#d97706] [&.is-pending]:text-[#92400e] [&.is-processing]:bg-accent/10 [&.is-processing]:border-accent [&.is-processing]:text-accent [&.is-shipped]:bg-accent/15 [&.is-shipped]:border-accent [&.is-shipped]:text-accent-dark [&.is-delivered]:bg-[rgba(21,128,61,0.1)] [&.is-delivered]:border-[#15803d] [&.is-delivered]:text-[#15803d] [&.is-cancelled]:bg-[rgba(182,29,15,0.08)] [&.is-cancelled]:border-announce [&.is-cancelled]:text-announce is-pending" data-i5="order-card__status">Teslim Edildi</span>
      </div>

      <div class="flex flex-wrap items-center gap-2.5 mb-7 pb-6 border-b-[3px] border-ink" data-i5="order-detail__actions">
        <a data-i5="btn--outline" data-i5-tags="btn btn--outline" href="cart.html" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover">Tekrar Sipariş Ver</a>
        <button data-i5="btn--outline" data-i5-tags="btn btn--outline" type="button" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover">Yazdır</button>
        <a data-i5="btn--outline" data-i5-tags="btn btn--outline" href="https://wa.me/905321234567?text=Merhaba%2C%20PMP-DEMO001%20numaral%C4%B1%20sipari%C5%9Fim%20hakk%C4%B1nda%20bilgi%20almak%20istiyorum." target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover">WhatsApp Destek</a>
        <a href="orders.html" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-muted ml-auto transition-colors hover:text-accent" data-i5="order-detail__back">← Tüm Siparişler</a>
      </div>

      <div class="grid gap-6 min-[960px]:grid-cols-[1fr_340px] min-[960px]:items-start" data-i5="order-detail__layout">
        <div class="grid gap-6" data-i5="order-detail__main">
          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Sipariş Durumu</h2>
            <div class="grid gap-0" data-i5="order-timeline">
              <div class="flex gap-4 relative pb-6 last:pb-0 is-done is-current group/step [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:left-[11px] [&:not(:last-child)]:after:top-6 [&:not(:last-child)]:after:bottom-0 [&:not(:last-child)]:after:w-0.5 [&:not(:last-child)]:after:bg-ink/20" data-i5="order-timeline__step">
                <div class="w-6 h-6 shrink-0 flex items-center justify-center border-2 border-hover bg-surface text-[11px] font-bold text-muted relative z-[1] group-[.is-done]/step:bg-action group-[.is-done]/step:border-ink group-[.is-done]/step:text-on-dark group-[.is-current]/step:bg-accent group-[.is-current]/step:border-ink group-[.is-current]/step:text-on-dark" data-i5="order-timeline__dot">✓</div>
                <span class="text-sm font-semibold pt-0.5 group-[.is-current]/step:text-accent" data-i5="order-timeline__label">Sipariş Alındı</span>
              </div>
              <div class="flex gap-4 relative pb-6 last:pb-0 is-done group/step [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:left-[11px] [&:not(:last-child)]:after:top-6 [&:not(:last-child)]:after:bottom-0 [&:not(:last-child)]:after:w-0.5 [&:not(:last-child)]:after:bg-ink/20" data-i5="order-timeline__step">
                <div class="w-6 h-6 shrink-0 flex items-center justify-center border-2 border-hover bg-surface text-[11px] font-bold text-muted relative z-[1] group-[.is-done]/step:bg-action group-[.is-done]/step:border-ink group-[.is-done]/step:text-on-dark group-[.is-current]/step:bg-accent group-[.is-current]/step:border-ink group-[.is-current]/step:text-on-dark" data-i5="order-timeline__dot">✓</div>
                <span class="text-sm font-semibold pt-0.5 group-[.is-current]/step:text-accent" data-i5="order-timeline__label">Üretimde</span>
              </div>
              <div class="flex gap-4 relative pb-6 last:pb-0 is-done group/step [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:left-[11px] [&:not(:last-child)]:after:top-6 [&:not(:last-child)]:after:bottom-0 [&:not(:last-child)]:after:w-0.5 [&:not(:last-child)]:after:bg-ink/20" data-i5="order-timeline__step">
                <div class="w-6 h-6 shrink-0 flex items-center justify-center border-2 border-hover bg-surface text-[11px] font-bold text-muted relative z-[1] group-[.is-done]/step:bg-action group-[.is-done]/step:border-ink group-[.is-done]/step:text-on-dark group-[.is-current]/step:bg-accent group-[.is-current]/step:border-ink group-[.is-current]/step:text-on-dark" data-i5="order-timeline__dot">✓</div>
                <span class="text-sm font-semibold pt-0.5 group-[.is-current]/step:text-accent" data-i5="order-timeline__label">Kargoya Verildi</span>
              </div>
              <div class="flex gap-4 relative pb-6 last:pb-0 is-done group/step [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:left-[11px] [&:not(:last-child)]:after:top-6 [&:not(:last-child)]:after:bottom-0 [&:not(:last-child)]:after:w-0.5 [&:not(:last-child)]:after:bg-ink/20" data-i5="order-timeline__step">
                <div class="w-6 h-6 shrink-0 flex items-center justify-center border-2 border-hover bg-surface text-[11px] font-bold text-muted relative z-[1] group-[.is-done]/step:bg-action group-[.is-done]/step:border-ink group-[.is-done]/step:text-on-dark group-[.is-current]/step:bg-accent group-[.is-current]/step:border-ink group-[.is-current]/step:text-on-dark" data-i5="order-timeline__dot">✓</div>
                <span class="text-sm font-semibold pt-0.5 group-[.is-current]/step:text-accent" data-i5="order-timeline__label">Teslim Edildi</span>
              </div>
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Kargo Takibi</h2>
            <div class="grid gap-3" data-i5="order-tracking">
              <div class="flex justify-between gap-3 text-sm [&_span]:text-muted" data-i5="order-tracking__row">
                <span>Kargo Firması</span>
                <strong>Yurtiçi Kargo</strong>
              </div>
              <div class="flex justify-between gap-3 text-sm [&_span]:text-muted" data-i5="order-tracking__row">
                <span>Takip No</span>
                <strong class="font-body tracking-[0.04em]" data-i5="order-tracking__code">734012345678</strong>
              </div>
              <button type="button" class="justify-self-start font-body text-[11px] font-bold uppercase tracking-[0.04em] px-3.5 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface transition-colors hover:bg-hover" data-i5="order-tracking__copy">Kopyala</button>
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Ürünler (2)</h2>
            <div class="grid grid-cols-[72px_1fr_auto] gap-4 items-center py-4 border-b-[3px] border-ink last:border-b-0 last:pb-0 first:pt-0" data-i5="order-detail-item">
              <a href="product.html" class="border-[3px] border-ink aspect-[3/4] overflow-hidden bg-bg block transition-shadow hover:shadow-brutal-sm [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="order-detail-item__img">
                <img src="assets/foto5.jpeg" alt="Premium Kartvizit">
              </a>
              <div>
                <a href="product.html" class="font-heading text-card-title font-semibold leading-snug normal-case inline-block mb-1 text-ink transition-colors hover:text-accent" data-i5="order-detail-item__name">Premium Kartvizit</a>
                <p class="text-[13px] text-muted" data-i5="order-detail-item__qty">2 adet × 350 ₺</p>
              </div>
              <span class="font-body font-bold text-sm" data-i5="order-detail-item__price">700 ₺</span>
            </div>
            <div class="grid grid-cols-[72px_1fr_auto] gap-4 items-center py-4 border-b-[3px] border-ink last:border-b-0 last:pb-0 first:pt-0" data-i5="order-detail-item">
              <a href="product.html" class="border-[3px] border-ink aspect-[3/4] overflow-hidden bg-bg block transition-shadow hover:shadow-brutal-sm [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="order-detail-item__img">
                <img src="assets/WhatsApp Image 2026-06-27 at 00.28.43.jpeg" alt="Magnet Afiş Seti">
              </a>
              <div>
                <a href="product.html" class="font-heading text-card-title font-semibold leading-snug normal-case inline-block mb-1 text-ink transition-colors hover:text-accent" data-i5="order-detail-item__name">Magnet Afiş Seti</a>
                <p class="text-[13px] text-muted" data-i5="order-detail-item__qty">1 adet × 420 ₺</p>
              </div>
              <span class="font-body font-bold text-sm" data-i5="order-detail-item__price">420 ₺</span>
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Teslimat Adresi</h2>
            <div class="text-sm leading-[1.7] text-muted [&_strong]:block [&_strong]:mb-1 [&_strong]:font-semibold [&_strong]:text-ink" data-i5="order-address">
              <strong>Demo Kullanıcı</strong>
              Örnek Mah. Baskı Sk. No:12<br>
              Kadıköy, İstanbul<br>
              0532 000 00 00
            </div>
          </section>
        </div>

        <aside class="grid gap-4" data-i5="order-detail__sidebar">
          <div class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Özet</h2>
            <div class="flex justify-between gap-3 text-sm py-2 text-muted [&_span:first-child]:text-muted" data-i5="order-summary__row">
              <span>Ara Toplam</span>
              <strong>1.151 ₺</strong>
            </div>
            <div class="flex justify-between gap-3 text-sm py-2 text-muted [&_span:first-child]:text-muted" data-i5="order-summary__row">
              <span>Kargo</span>
              <strong>89 ₺</strong>
            </div>
            <div class="flex justify-between gap-3 text-sm py-2 text-muted [&_span:first-child]:text-muted" data-i5="order-summary__row">
              <span>Ödeme</span>
              <strong>Kredi / Banka Kartı</strong>
            </div>
            <div class="flex justify-between gap-3 text-sm py-2 text-muted [&_span:first-child]:text-muted" data-i5="order-summary__row">
              <span>Durum</span>
              <strong>Teslim Edildi</strong>
            </div>
            <div class="flex justify-between gap-3 pt-4 mt-2 border-t-[3px] border-ink font-body text-lg font-bold" data-i5="order-summary__total">
              <span>Toplam</span>
              <span>1.240 ₺</span>
            </div>
            <div class="mt-5 pt-5 border-t-[3px] border-ink text-[13px] text-muted leading-normal [&_a]:inline-block [&_a]:mt-2 [&_a]:font-bold [&_a]:text-accent [&_a]:underline [&_a]:underline-offset-[3px] hover:[&_a]:text-ink" data-i5="order-summary__support">
              Sorularınız mı var?
              <a href="https://wa.me/905321234567?text=Merhaba%2C%20PMP-DEMO001%20numaral%C4%B1%20sipari%C5%9Fim%20hakk%C4%B1nda%20bilgi%20almak%20istiyorum." target="_blank" rel="noopener noreferrer">WhatsApp ile destek al →</a>
            </div>
          </div>

          <div class="border-[3px] border-ink shadow-brutal-sm bg-bg p-5 [&_strong]:block [&_strong]:mb-2 [&_strong]:font-body [&_strong]:text-xs [&_strong]:font-bold [&_strong]:uppercase [&_strong]:tracking-[0.06em] [&_strong]:text-ink" data-i5="order-detail__help">
            <strong>Yardım</strong>
            <p>Siparişinizle ilgili dosya yükleme veya değişiklik talepleri için destek ekibimizle iletişime geçin.</p>
            <a data-i5="order-detail__help-btn" data-i5-tags="btn btn--fill order-detail__help-btn" href="https://wa.me/905321234567?text=Merhaba%2C%20PMP-DEMO001%20numaral%C4%B1%20sipari%C5%9Fim%20hakk%C4%B1nda%20bilgi%20almak%20istiyorum." target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5 w-full justify-center text-center">Destek Al</a>
          </div>
        </aside>
      </div>
    </div>
  </main>
@endsection