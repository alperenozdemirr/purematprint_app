@extends('user.layout')
@section('title','Kayıt Ol')
@section('content')
<main id="register-root" class="py-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Kayıt</span>
      </nav>

      <div class="grid gap-0 border-[3px] border-ink shadow-brutal bg-surface overflow-hidden min-[960px]:grid-cols-2 min-[960px]:min-h-[560px]" data-i5="login-layout">
        <div class="p-10 px-7 min-[960px]:p-14 min-[960px]:px-12 min-[960px]:flex min-[960px]:flex-col min-[960px]:justify-center [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case [&_h1]:mb-2 [&>p]:text-muted [&>p]:text-[15px] [&>p]:leading-[1.6] [&>p]:mb-8" data-i5="login-form-panel">
          <h1>Kayıt Ol</h1>
          <p>Ücretsiz hesap oluşturun; siparişlerinizi takip edin ve hızlıca alışverişe devam edin.</p>

          @if (session('success'))
          <div class="p-3.5 mb-5 border-[3px] border-ink bg-bg text-sm font-semibold text-ink" role="alert">{{ session('success') }}</div>
          @endif
          @if (session('error'))
          <div class="p-3.5 mb-5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
          @endif
          @if ($errors->any())
          <div class="p-3.5 mb-5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">
            <ul class="grid gap-1">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form class="grid gap-5" id="register-form" action="{{ route('register') }}" method="post" data-i5="login-form">
            @csrf
            <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm" data-i5="login-field">
              <label for="register-name">Ad Soyad</label>
              <input type="text" id="register-name" name="name" value="{{ old('name') }}" required placeholder="Adınız Soyadınız" autocomplete="name">
            </div>
            <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm" data-i5="login-field">
              <label for="register-email">E-posta</label>
              <input type="email" id="register-email" name="email" value="{{ old('email') }}" required placeholder="ornek@firma.com" autocomplete="email">
            </div>
            <div class="grid gap-5 min-[560px]:grid-cols-2" data-i5="register-row">
              <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm" data-i5="login-field">
                <label for="register-password">Şifre</label>
                <div class="relative [&_input]:w-full [&_input]:pr-12" data-i5="login-field__wrap">
                  <input type="password" id="register-password" name="password" required placeholder="••••••••" autocomplete="new-password" minlength="6">
                  <button type="button" class="absolute right-0 top-0 bottom-0 w-11 flex items-center justify-center text-muted transition-colors hover:text-ink" aria-label="Şifreyi göster" data-i5="login-toggle-pw">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M2.036 12.593a1 1 0 010-.186C3.54 7.442 7.674 4.5 12 4.5c4.326 0 8.46 2.942 9.964 6.907a1 1 0 010 .186C20.46 16.558 16.326 19.5 12 19.5c-4.326 0-8.46-2.942-9.964-6.907z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </button>
                </div>
                <p class="text-[11px] font-semibold text-muted mt-1" data-i5="password-strength-label">En az 6 karakter</p>
              </div>
              <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm" data-i5="login-field">
                <label for="register-confirm">Şifre Tekrar</label>
                <div class="relative [&_input]:w-full [&_input]:pr-12" data-i5="login-field__wrap">
                  <input type="password" id="register-confirm" name="confirm" required placeholder="••••••••" autocomplete="new-password" minlength="6">
                  <button type="button" class="absolute right-0 top-0 bottom-0 w-11 flex items-center justify-center text-muted transition-colors hover:text-ink" aria-label="Şifreyi göster" data-i5="login-toggle-pw">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M2.036 12.593a1 1 0 010-.186C3.54 7.442 7.674 4.5 12 4.5c4.326 0 8.46 2.942 9.964 6.907a1 1 0 010 .186C20.46 16.558 16.326 19.5 12 19.5c-4.326 0-8.46-2.942-9.964-6.907z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </button>
                </div>
              </div>
            </div>
            <label class="flex items-start gap-2.5 text-[13px] leading-normal text-muted cursor-pointer [&_input]:w-4 [&_input]:h-4 [&_input]:mt-0.5 [&_input]:accent-accent [&_input]:shrink-0 [&_a]:text-accent [&_a]:font-semibold [&_a]:underline [&_a]:underline-offset-[3px] [&_a:hover]:text-accent-dark" data-i5="register-terms">
              <input type="checkbox" id="register-terms" name="terms" value="1" @checked(old('terms')) required>
              <span><a href="distance-sales.html">Mesafeli Satış Sözleşmesi</a> ve <a href="privacy.html">Gizlilik Politikası</a>'nı okudum, kabul ediyorum.</span>
            </label>
            <button data-i5="login-submit" data-i5-tags="btn btn--fill login-submit" type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5 w-full justify-center text-center">Hesap Oluştur</button>
          </form>

          <div class="grid gap-2.5 mt-5 p-3.5 px-4 bg-bg border-[3px] border-ink [&_strong]:block [&_strong]:font-body [&_strong]:text-[11px] [&_strong]:font-bold [&_strong]:uppercase [&_strong]:text-ink [&_strong]:mb-1 [&_ul]:grid [&_ul]:gap-2 [&_li]:flex [&_li]:items-start [&_li]:gap-2 [&_li]:text-[13px] [&_li]:text-muted [&_li]:leading-snug [&_li_svg]:shrink-0 [&_li_svg]:mt-px [&_li_svg]:text-accent" data-i5="register-benefits">
            <strong>Hesap avantajları</strong>
            <ul>
              <li>
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                Sipariş geçmişi ve takip
              </li>
              <li>
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                Kayıtlı adreslerle hızlı ödeme
              </li>
              <li>
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                Özel kampanya ve teklifler
              </li>
            </ul>
          </div>

          <div class="flex items-center gap-4 my-7 font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted before:flex-1 before:h-0.5 before:bg-hover after:flex-1 after:h-0.5 after:bg-hover" data-i5="login-divider">veya</div>

          <div class="grid grid-cols-2 gap-2.5" data-i5="login-social">
            <button type="button" class="flex items-center justify-center gap-2 p-3 border-[3px] border-ink shadow-brutal-sm bg-surface font-body text-[11px] font-bold uppercase tracking-[0.04em] transition-[background,transform] hover:bg-hover hover:-translate-x-px hover:-translate-y-px" data-i5="login-social__btn">
              <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
              Google
            </button>
            <button type="button" class="flex items-center justify-center gap-2 p-3 border-[3px] border-ink shadow-brutal-sm bg-surface font-body text-[11px] font-bold uppercase tracking-[0.04em] transition-[background,transform] hover:bg-hover hover:-translate-x-px hover:-translate-y-px" data-i5="login-social__btn">
              <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
              Apple
            </button>
          </div>

          <p class="mt-7 pt-6 border-t-[3px] border-ink text-center text-sm text-muted [&_a]:font-bold [&_a]:text-accent [&_a]:underline [&_a]:underline-offset-[3px] [&_a:hover]:text-accent-dark" data-i5="login-footer-text">
            Zaten hesabınız var mı? <a href="{{ route('loginPage') }}">Giriş yapın</a>
          </p>
        </div>

        <div class="relative hidden bg-dark text-on-dark overflow-hidden min-[960px]:flex min-[960px]:flex-col min-[960px]:justify-end min-[960px]:min-h-full" data-i5="login-brand">
          <div class="absolute inset-0 [&_img]:w-full [&_img]:h-full [&_img]:object-cover [&_img]:opacity-45" data-i5="login-brand__img">
            <img src="{{asset('user')}}/assets/foto2.jpeg" alt="">
          </div>
          <div class="relative z-[1] p-12 border-t-[3px] border-ink bg-gradient-to-t from-[rgba(42,40,38,0.92)] to-transparent [&_blockquote]:font-body [&_blockquote]:text-[1.35rem] [&_blockquote]:font-bold [&_blockquote]:leading-[1.2] [&_blockquote]:uppercase [&_blockquote]:tracking-[-0.02em] [&_blockquote]:mb-3 [&_cite]:text-[13px] [&_cite]:not-italic [&_cite]:opacity-70" data-i5="login-brand__content">
            <p class="font-body text-[11px] font-bold tracking-[0.12em] uppercase text-white/65 mb-3" data-i5="login-brand__tag">PureMatPrint</p>
            <blockquote>"Kaliteli baskı, güçlü marka."</blockquote>
            <cite>— PureMatPrint Ekibi</cite>
            <ul class="mt-5 grid gap-2.5 [&_li]:flex [&_li]:items-center [&_li]:gap-2.5 [&_li]:text-[13px] [&_li]:opacity-85 [&_li]:before:content-[''] [&_li]:before:w-1.5 [&_li]:before:h-1.5 [&_li]:before:bg-accent [&_li]:before:shrink-0" data-i5="login-brand__benefits">
              <li>500₺ üzeri ücretsiz kargo</li>
              <li>Profesyonel baskı kalitesi</li>
              <li>Hızlı teslimat</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection