@extends('admin.layout')
@section('title', 'Hesabım')
@section('page_title', 'Hesabım')
@section('breadcrumb', 'Sistem / Hesabım')

@section('content')
  <div class="mb-6">
    <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Hesap Ayarları</h2>
    <p class="font-body text-[13px] text-muted">Kişisel bilgilerinizi ve şifrenizi yönetin</p>
  </div>

  @if (session('success'))
    <div class="mb-5 rounded-xl border border-success/20 bg-success/5 px-4 py-3">
      <p class="text-sm font-medium text-success">{{ session('success') }}</p>
    </div>
  @endif

  <div class="grid gap-6 xl:grid-cols-2">
    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Profil Bilgileri</h3>
      </div>
      <form action="{{ route('admin.accountUpdate') }}" method="POST" class="grid gap-5 p-5">
        @csrf
        <div>
          <label for="name" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ad Soyad</label>
          <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('name') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="email" class="mb-1.5 block font-body text-[13px] font-bold text-ink">E-posta</label>
          <input type="email" id="email" value="{{ $user->email }}" disabled
                 class="w-full rounded-lg border border-ink/10 bg-cream/60 px-3.5 py-2.5 font-body text-[14px] text-muted outline-none cursor-not-allowed">
        </div>
        <div>
          <label for="phone" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Telefon</label>
          <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="05XX XXX XX XX">
          @error('phone') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="inline-flex w-full max-w-xs items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
          Bilgileri Kaydet
        </button>
      </form>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Şifre Değiştir</h3>
      </div>
      <form action="{{ route('admin.accountPasswordUpdate') }}" method="POST" class="grid gap-5 p-5">
        @csrf
        <div>
          <label for="current_password" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Mevcut Şifre</label>
          <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('current_password') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="password" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Yeni Şifre</label>
          <input type="password" id="password" name="password" required minlength="6" autocomplete="new-password"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('password') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="password_confirmation" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Yeni Şifre Tekrar</label>
          <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6" autocomplete="new-password"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
        </div>
        <p class="font-body text-[12px] text-muted">Şifreniz değiştirildiğinde güvenlik amacıyla e-posta adresinize bilgilendirme gönderilir.</p>
        <button type="submit" class="inline-flex w-full max-w-xs items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
          Şifreyi Güncelle
        </button>
      </form>
    </section>
  </div>
@endsection
