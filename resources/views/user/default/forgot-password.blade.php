@extends('user.layout')
@section('title','Şifremi Unuttum')
@section('content')
 <main id="forgot-password-root" class="py-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <a href="{{ route('loginPage') }}">Giriş</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Şifremi Unuttum</span>
      </nav>

      <div class="grid gap-0 border-[3px] border-ink shadow-brutal bg-surface overflow-hidden min-[960px]:grid-cols-2 min-[960px]:min-h-[560px]" data-i5="login-layout">
        <div class="p-10 px-7 min-[960px]:p-14 min-[960px]:px-12 min-[960px]:flex min-[960px]:flex-col min-[960px]:justify-center [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case [&_h1]:mb-2 [&>p]:text-muted [&>p]:text-[15px] [&>p]:leading-[1.6] [&>p]:mb-8" data-i5="login-form-panel">
          <h1>Şifremi Unuttum</h1>
          <p>Kayıtlı e-posta adresinizi girin; size şifrenizi sıfırlamanız için bir bağlantı gönderelim.</p>

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

          <form class="grid gap-5" id="forgot-password-form" action="{{ route('password.email') }}" method="post" data-i5="login-form">
            @csrf
            <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm" data-i5="login-field">
              <label for="forgot-email">E-posta</label>
              <input type="email" id="forgot-email" name="email" value="{{ old('email') }}" required placeholder="ornek@firma.com" autocomplete="email">
            </div>
            <button data-i5="forgot-submit" type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5 w-full justify-center text-center">Sıfırlama Bağlantısı Gönder</button>
          </form>

          <p class="mt-7 pt-6 border-t-[3px] border-ink text-center text-sm text-muted [&_a]:font-bold [&_a]:text-accent [&_a]:underline [&_a]:underline-offset-[3px] [&_a:hover]:text-accent-dark" data-i5="login-footer-text">
            Şifrenizi hatırladınız mı? <a href="{{ route('loginPage') }}">Giriş yapın</a>
          </p>
        </div>

        <div class="relative hidden bg-dark text-on-dark overflow-hidden min-[960px]:flex min-[960px]:flex-col min-[960px]:justify-end min-[960px]:min-h-full" data-i5="login-brand">
          <div class="absolute inset-0 [&_img]:w-full [&_img]:h-full [&_img]:object-cover [&_img]:opacity-45" data-i5="login-brand__img">
            <img src="{{asset('user')}}/assets/foto1.jpeg" alt="">
          </div>
          <div class="relative z-[1] p-12 border-t-[3px] border-ink bg-gradient-to-t from-[rgba(42,40,38,0.92)] to-transparent [&_blockquote]:font-body [&_blockquote]:text-[1.35rem] [&_blockquote]:font-bold [&_blockquote]:leading-[1.2] [&_blockquote]:uppercase [&_blockquote]:tracking-[-0.02em] [&_blockquote]:mb-3 [&_cite]:text-[13px] [&_cite]:not-italic [&_cite]:opacity-70" data-i5="login-brand__content">
            <p class="font-body text-[11px] font-bold tracking-[0.12em] uppercase text-white/65 mb-3" data-i5="login-brand__tag">PureMatPrint</p>
            <blockquote>"Güvenli hesap, kesintisiz alışveriş."</blockquote>
            <cite>— PureMatPrint Ekibi</cite>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
