@extends('user.layout')
@section('title','Hesabım')
@section('content')
<main class="pt-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Profil Bilgilerim</span>
      </nav>

      <div class="grid gap-6 items-start min-[960px]:grid-cols-[260px_1fr] min-[960px]:gap-8" data-i5="account-layout">
        @include('user.partials.account-nav', ['activeNav' => $activeNav ?? 'account'])

        <div>
          <div class="mb-7 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="profile-page__head">
            <h1>Profil Bilgilerim</h1>
            <p class="mt-2.5 text-sm text-muted font-semibold" data-i5="profile-page__sub">Kişisel bilgilerinizi ve hesap tercihlerinizi yönetin</p>
          </div>

          @if (session('success'))
          <div class="mb-5 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-ink" role="alert">{{ session('success') }}</div>
          @endif
          @if (session('error'))
          <div class="mb-5 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
          @endif

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface mb-5 overflow-hidden" data-i5="profile-panel">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_p]:mt-1 [&_p]:text-[13px] [&_p]:text-muted" data-i5="profile-panel__head">
              <h2>Kişisel Bilgiler</h2>
              <p>Sipariş ve fatura süreçlerinde kullanılır</p>
            </div>
            <form class="p-6 px-5 grid gap-5" action="{{ route('accountUpdate') }}" method="post" data-i5="profile-form">
              @csrf
              <div class="grid gap-4 min-[640px]:grid-cols-2" data-i5="profile-form__grid">
                <div data-i5="profile-form__grid--full" class="flex flex-col gap-1.5 min-[640px]:col-span-full [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed" data-i5="profile-field">
                  <label for="profile-name">Ad Soyad *</label>
                  <input type="text" id="profile-name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                  @error('name')<span class="text-xs text-announce">{{ $message }}</span>@enderror
                </div>
                <div data-i5="profile-form__grid--full" class="flex flex-col gap-1.5 min-[640px]:col-span-full [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_input:disabled]:bg-bg [&_input:disabled]:text-muted [&_input:disabled]:cursor-not-allowed" data-i5="profile-field">
                  <label for="profile-email">E-posta</label>
                  <input type="email" id="profile-email" name="email" value="{{ $user->email }}" disabled autocomplete="email">
                </div>
                <div class="flex flex-col gap-1.5 min-[640px]:col-span-full [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm" data-i5="profile-field">
                  <label for="profile-phone">Telefon *</label>
                  <input type="tel" id="profile-phone" name="phone" value="{{ old('phone', $user->phone) }}" required autocomplete="tel" placeholder="05XX XXX XX XX">
                  @error('phone')<span class="text-xs text-announce">{{ $message }}</span>@enderror
                </div>
              </div>
              <div class="flex flex-wrap gap-2.5 pt-1" data-i5="profile-form__actions">
                <button data-i5="btn--fill" type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">Değişiklikleri Kaydet</button>
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
                <div data-i5="profile-form__grid--full" class="flex flex-col gap-1.5 min-[640px]:col-span-full [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm">
                  <label for="profile-current-pw">Mevcut Şifre *</label>
                  <input type="password" id="profile-current-pw" name="current_password" autocomplete="current-password" placeholder="••••••••" disabled>
                </div>
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm" data-i5="profile-field">
                  <label for="profile-new-pw">Yeni Şifre *</label>
                  <input type="password" id="profile-new-pw" name="new_password" autocomplete="new-password" minlength="6" placeholder="••••••••" disabled>
                </div>
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm" data-i5="profile-field">
                  <label for="profile-confirm-pw">Yeni Şifre Tekrar *</label>
                  <input type="password" id="profile-confirm-pw" name="confirm_password" autocomplete="new-password" minlength="6" placeholder="••••••••" disabled>
                </div>
              </div>
              <p class="text-xs text-muted">Şifre değiştirme özelliği yakında eklenecek.</p>
            </form>
          </section>
        </div>
      </div>
    </div>
  </main>
@endsection
