@extends('user.layout')
@section('title','Hesabım')
@section('content')
<main class="pt-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="index.html">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Profil Bilgilerim</span>
      </nav>

      <div class="grid gap-6 items-start min-[960px]:grid-cols-[260px_1fr] min-[960px]:gap-8" data-i5="account-layout">
        <aside class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="account-nav">
          <div class="flex items-center gap-3.5 px-[18px] py-5 border-b-[3px] border-ink bg-bg" data-i5="account-nav__user">
            <span class="w-12 h-12 flex items-center justify-center font-body text-sm font-bold uppercase tracking-[0.04em] bg-accent text-on-dark border-[3px] border-ink shrink-0" aria-hidden="true" data-i5="account-nav__avatar">DK</span>
            <div>
              <p class="font-body text-sm font-bold uppercase tracking-[0.02em] mb-0.5" data-i5="account-nav__name">Demo Kullanıcı</p>
              <p class="text-xs text-muted break-all" data-i5="account-nav__email">demo@purematprint.com</p>
            </div>
          </div>
          <nav class="grid p-2" aria-label="Hesap menüsü" data-i5="account-nav__links">
            <a href="profile.html" class="flex items-center gap-2.5 px-3.5 py-3 font-body text-xs font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink is-active [&_svg]:shrink-0 [&_svg]:opacity-70 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active_svg]:opacity-100" data-i5="account-nav__link">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
              Profil Bilgilerim
            </a>
            <a href="orders.html" class="flex items-center gap-2.5 px-3.5 py-3 font-body text-xs font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&_svg]:shrink-0 [&_svg]:opacity-70 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active_svg]:opacity-100" data-i5="account-nav__link">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
              Siparişlerim
            </a>
            <a href="addresses.html" class="flex items-center gap-2.5 px-3.5 py-3 font-body text-xs font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&_svg]:shrink-0 [&_svg]:opacity-70 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active_svg]:opacity-100" data-i5="account-nav__link">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
              Adreslerim
            </a>
          </nav>
          <a href="login.html" class="block mx-2 mb-2 px-3.5 py-3 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-center text-announce border-2 border-announce transition-[background,color] hover:bg-announce hover:text-on-dark" data-i5="account-nav__logout">Çıkış Yap</a>
        </aside>

        <div >
          <div class="mb-7 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="profile-page__head">
            <h1>Profil Bilgilerim</h1>
            <p class="mt-2.5 text-sm text-muted font-semibold" data-i5="profile-page__sub">Kişisel bilgilerinizi ve hesap tercihlerinizi yönetin</p>
          </div>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface mb-5 overflow-hidden" data-i5="profile-panel">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_p]:mt-1 [&_p]:text-[13px] [&_p]:text-muted" data-i5="profile-panel__head">
              <h2>Kişisel Bilgiler</h2>
              <p>Sipariş ve fatura süreçlerinde kullanılır</p>
            </div>
            <form class="p-6 px-5 grid gap-5" action="#" method="post" data-i5="profile-form">
              <div class="grid gap-4 min-[640px]:grid-cols-2" data-i5="profile-form__grid">
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed" data-i5="profile-field">
                  <label for="profile-firstname">Ad *</label>
                  <input type="text" id="profile-firstname" name="firstname" value="Demo" required autocomplete="given-name">
                </div>
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed" data-i5="profile-field">
                  <label for="profile-lastname">Soyad *</label>
                  <input type="text" id="profile-lastname" name="lastname" value="Kullanıcı" required autocomplete="family-name">
                </div>
                <div data-i5="profile-form__grid--full" data-i5-tags="profile-field profile-form__grid--full" class="flex flex-col gap-1.5 min-[640px]:col-span-full [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed">
                  <label for="profile-email">E-posta *</label>
                  <input type="email" id="profile-email" name="email" value="demo@purematprint.com" required autocomplete="email">
                </div>
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed" data-i5="profile-field">
                  <label for="profile-phone">Telefon *</label>
                  <input type="tel" id="profile-phone" name="phone" value="0532 000 00 00" required autocomplete="tel">
                </div>
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed" data-i5="profile-field">
                  <label for="profile-company">Firma</label>
                  <input type="text" id="profile-company" name="company" value="PureMatPrint Ltd." autocomplete="organization">
                </div>
                <div data-i5="profile-form__grid--full" data-i5-tags="profile-field profile-form__grid--full" class="flex flex-col gap-1.5 min-[640px]:col-span-full [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed">
                  <label for="profile-tax">Vergi No / TCKN</label>
                  <input type="text" id="profile-tax" name="tax_id" value="1234567890" placeholder="Kurumsal fatura için">
                  <span class="text-xs text-muted" data-i5="profile-field__hint">Kurumsal fatura kesilecekse zorunludur</span>
                </div>
              </div>
              <div class="flex flex-wrap gap-2.5 pt-1" data-i5="profile-form__actions">
                <button data-i5="btn--fill" data-i5-tags="btn btn--fill" type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">Değişiklikleri Kaydet</button>
              </div>
            </form>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface mb-5 overflow-hidden" data-i5="profile-panel">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_p]:mt-1 [&_p]:text-[13px] [&_p]:text-muted" data-i5="profile-panel__head">
              <h2>Şifre Değiştir</h2>
              <p>Hesabınızın güvenliği için güçlü bir şifre kullanın</p>
            </div>
            <form class="p-6 px-5 grid gap-5" action="#" method="post" data-i5="profile-form">
              <div class="grid gap-4 min-[640px]:grid-cols-2" data-i5="profile-form__grid">
                <div data-i5="profile-form__grid--full" data-i5-tags="profile-field profile-form__grid--full" class="flex flex-col gap-1.5 min-[640px]:col-span-full [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed">
                  <label for="profile-current-pw">Mevcut Şifre *</label>
                  <input type="password" id="profile-current-pw" name="current_password" autocomplete="current-password" placeholder="••••••••">
                </div>
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed" data-i5="profile-field">
                  <label for="profile-new-pw">Yeni Şifre *</label>
                  <input type="password" id="profile-new-pw" name="new_password" autocomplete="new-password" minlength="6" placeholder="••••••••">
                  <span class="text-xs text-muted" data-i5="profile-field__hint">En az 6 karakter</span>
                </div>
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed" data-i5="profile-field">
                  <label for="profile-confirm-pw">Yeni Şifre Tekrar *</label>
                  <input type="password" id="profile-confirm-pw" name="confirm_password" autocomplete="new-password" minlength="6" placeholder="••••••••">
                </div>
              </div>
              <div class="flex flex-wrap gap-2.5 pt-1" data-i5="profile-form__actions">
                <button data-i5="btn--outline" data-i5-tags="btn btn--outline" type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover">Şifreyi Güncelle</button>
              </div>
            </form>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface mb-5 overflow-hidden" data-i5="profile-panel">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_p]:mt-1 [&_p]:text-[13px] [&_p]:text-muted" data-i5="profile-panel__head">
              <h2>Bildirim Tercihleri</h2>
              <p>E-posta bildirimlerinizi özelleştirin</p>
            </div>
            <form class="p-6 px-5 grid gap-5" action="#" method="post" data-i5="profile-form">
              <div class="grid gap-3" data-i5="profile-checks">
                <label class="flex items-start gap-2.5 text-sm leading-normal cursor-pointer [&_input]:w-4 [&_input]:h-4 [&_input]:mt-0.5 [&_input]:accent-accent [&_input]:shrink-0" data-i5="profile-check">
                  <input type="checkbox" name="notify_orders" checked>
                  Sipariş durumu güncellemeleri
                </label>
                <label class="flex items-start gap-2.5 text-sm leading-normal cursor-pointer [&_input]:w-4 [&_input]:h-4 [&_input]:mt-0.5 [&_input]:accent-accent [&_input]:shrink-0" data-i5="profile-check">
                  <input type="checkbox" name="notify_campaigns" checked>
                  Kampanya ve indirim duyuruları
                </label>
                <label class="flex items-start gap-2.5 text-sm leading-normal cursor-pointer [&_input]:w-4 [&_input]:h-4 [&_input]:mt-0.5 [&_input]:accent-accent [&_input]:shrink-0" data-i5="profile-check">
                  <input type="checkbox" name="notify_newsletter">
                  Baskı trendleri ve sektör haberleri bülteni
                </label>
              </div>
              <div class="flex flex-wrap gap-2.5 pt-1" data-i5="profile-form__actions">
                <button data-i5="btn--fill" data-i5-tags="btn btn--fill" type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">Tercihleri Kaydet</button>
              </div>
            </form>
          </section>

          <section data-i5="profile-danger" data-i5-tags="profile-panel profile-danger" class="border-[3px] border-ink shadow-brutal-sm bg-surface mb-5 overflow-hidden border-announce [&_[data-i5='profile-panel__head']]:bg-[rgba(182,29,15,0.06)] [&_[data-i5='profile-panel__head']_h2]:text-announce [&_p]:px-5 [&_p]:text-sm [&_p]:text-muted [&_p]:leading-[1.6]">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_p]:mt-1 [&_p]:text-[13px] [&_p]:text-muted" data-i5="profile-panel__head">
              <h2>Hesabı Sil</h2>
            </div>
            <p>Hesabınızı sildiğinizde tüm sipariş geçmişi ve kayıtlı adresler kalıcı olarak silinir. Bu işlem geri alınamaz.</p>
            <div class="px-5 pb-6" data-i5="profile-danger__actions">
              <button type="button" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] px-4 py-2.5 border-2 border-announce text-announce bg-transparent transition-[background,color] hover:bg-announce hover:text-on-dark" data-i5="profile-danger__btn">Hesabımı Sil</button>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>
@endsection