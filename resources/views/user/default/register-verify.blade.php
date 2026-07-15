@extends('user.layout')
@section('title','E-posta Doğrulama')
@section('content')
<main id="register-verify-root" class="py-8 pb-20">
  <div class="w-full max-w-site mx-auto px-5 lg:px-8">
    <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5" aria-label="Konum">
      <a href="{{ route('index') }}">Anasayfa</a>
      <span class="opacity-[0.4]">/</span>
      <a href="{{ route('registerPage') }}">Kayıt</a>
      <span class="opacity-[0.4]">/</span>
      <span>Doğrulama</span>
    </nav>

    <div class="grid gap-0 border-[3px] border-ink shadow-brutal bg-surface overflow-hidden min-[960px]:grid-cols-2 min-[960px]:min-h-[560px]">
      <div class="p-10 px-7 min-[960px]:p-14 min-[960px]:px-12 min-[960px]:flex min-[960px]:flex-col min-[960px]:justify-center">
        <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] normal-case mb-2">E-postanızı Doğrulayın</h1>
        <p class="text-muted text-[15px] leading-[1.6] mb-8">
          <span class="font-semibold text-ink">{{ $maskedEmail }}</span> adresine 6 haneli bir kod gönderdik. Kodu girerek kaydınızı tamamlayın.
        </p>

        <div id="verify-alert" class="hidden p-3.5 mb-5 border-[3px] border-ink text-sm font-semibold" role="alert"></div>

        <form id="register-verify-form" class="grid gap-5" data-send-url="{{ route('registerVerifySend') }}" data-verify-url="{{ route('registerVerify') }}" data-login-url="{{ route('loginPage') }}">
          @csrf
          <div>
            <label for="verify-code" class="mb-2 block font-body text-[11px] font-bold uppercase tracking-[0.06em] text-ink">Doğrulama Kodu</label>
            <input
              type="text"
              id="verify-code"
              name="code"
              inputmode="numeric"
              pattern="[0-9]*"
              maxlength="6"
              autocomplete="one-time-code"
              placeholder="000000"
              class="w-full px-3.5 py-[13px] border-[3px] border-ink text-[24px] font-bold tracking-[0.35em] text-center bg-surface outline-none focus:shadow-brutal-sm"
              required
            >
            <p class="mt-2 text-[12px] text-muted">Kod 10 dakika geçerlidir.</p>
          </div>

          <button type="submit" id="verify-submit" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover w-full">
            Doğrula ve Kaydı Tamamla
          </button>
        </form>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <button type="button" id="verify-resend" class="font-body text-[12px] font-bold uppercase tracking-[0.06em] text-accent underline underline-offset-[3px] hover:text-accent-dark disabled:opacity-50 disabled:cursor-not-allowed">
            Kodu Tekrar Gönder
          </button>
          <a href="{{ route('registerPage') }}" class="font-body text-[12px] font-semibold text-muted hover:text-accent">Kayıt formuna dön</a>
        </div>
      </div>

      <div class="relative hidden bg-dark text-on-dark overflow-hidden min-[960px]:flex min-[960px]:flex-col min-[960px]:justify-end min-[960px]:min-h-full">
        <div class="absolute inset-0">
          <img src="{{ asset('user/assets/foto2.jpeg') }}" alt="" class="w-full h-full object-cover opacity-45">
        </div>
        <div class="relative z-[1] p-12 border-t-[3px] border-ink bg-gradient-to-t from-[rgba(42,40,38,0.92)] to-transparent">
          <p class="font-body text-[11px] font-bold tracking-[0.12em] uppercase text-white/65 mb-3">Güvenli Kayıt</p>
          <blockquote class="font-body text-[1.35rem] font-bold leading-[1.2] uppercase tracking-[-0.02em] mb-3">"Hesabınızı doğrulayın, alışverişe başlayın."</blockquote>
          <p class="text-[13px] leading-relaxed opacity-80">E-posta doğrulaması hesabınızın güvenliğini artırır ve sipariş bildirimlerinin size ulaşmasını sağlar.</p>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('user/js/register-verify.js') }}"></script>
@endpush
