<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="favicon.avif" type="image/avif">
  <title>Giriş — PureMatPrint Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            bg: '#faf6ee',
            surface: '#fffdf8',
            cream: '#fbf8f1',
            ink: '#1a1a1a',
            muted: '#5e5a54',
            accent: '#354e9c',
            'accent-dark': '#283c78',
            action: '#5a544e',
            'on-dark': '#faf6ee',
            dark: '#2a2826',
            hover: '#f2ece3',
            success: '#2f7a4d',
            danger: '#b61d0f',
          },
          fontFamily: {
            heading: ['Petrona', 'Georgia', 'Times New Roman', 'serif'],
            body: ['IBM Plex Sans', 'system-ui', '-apple-system', 'sans-serif'],
          },
          boxShadow: {
            card: '0 1px 2px rgba(26,26,26,0.04), 0 4px 16px rgba(26,26,26,0.06)',
            'card-md': '0 2px 8px rgba(26,26,26,0.06), 0 8px 24px rgba(26,26,26,0.05)',
          },
        },
      },
    };
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Petrona:wght@500;600;700&display=swap">
</head>
<body class="font-body text-base leading-[1.55] text-ink bg-bg antialiased min-h-dvh">

  <div class="grid min-h-dvh lg:grid-cols-2">
    {{-- Sol: marka paneli --}}
    <div class="relative hidden overflow-hidden bg-dark lg:flex lg:flex-col lg:justify-between">
      <div class="absolute inset-0">
        <img src="{{ asset('user') }}/assets/foto3.jpeg" alt="" class="h-full w-full object-cover opacity-35">
        <div class="absolute inset-0 bg-gradient-to-br from-dark via-dark/90 to-accent/40"></div>
      </div>
      <div class="relative z-[1] p-10">
        @include('user.partials.site-logo', ['class' => 'h-8 w-auto', 'invertOnDark' => true])
        <p class="mt-3 font-body text-[12px] font-bold uppercase tracking-[0.14em] text-on-dark/50">Yönetim Paneli</p>
      </div>
      <div class="relative z-[1] p-10">
        <blockquote class="font-heading text-[clamp(1.5rem,2.5vw,2rem)] font-bold leading-tight tracking-tight text-on-dark">
          "Markanı yönet, üretimi takip et, her detayı kontrol altında tut."
        </blockquote>
        <p class="mt-4 font-body text-[14px] text-on-dark/65">PureMatPrint Admin — baskı & tabela stüdyosu yönetim merkezi</p>
      </div>
    </div>

    {{-- Sağ: giriş formu --}}
    <div class="flex flex-col items-center justify-center px-5 py-10 sm:px-8">
      <div class="w-full max-w-[420px]">
        {{-- Mobil logo --}}
        <div class="mb-8 text-center lg:hidden">
          @include('user.partials.site-logo', ['class' => 'mx-auto h-7 w-auto'])
          <p class="mt-2 font-body text-[12px] font-bold uppercase tracking-[0.12em] text-muted">Admin Girişi</p>
        </div>

        <div class="rounded-2xl bg-surface p-7 shadow-card-md sm:p-8">
          <h1 class="font-heading text-[26px] font-bold leading-tight text-ink">Hoş geldiniz</h1>
          <p class="mt-1.5 font-body text-[14px] text-muted">Yönetim paneline erişmek için giriş yapın</p>

          @if (session('success'))
            <div class="mt-5 flex items-start gap-3 rounded-xl border border-success/20 bg-success/5 px-4 py-3">
              <p class="text-sm font-medium text-success">{{ session('success') }}</p>
            </div>
          @endif
          @if (session('error'))
            <div class="mt-5 flex items-start gap-3 rounded-xl border border-danger/20 bg-danger/5 px-4 py-3">
              <p class="text-sm font-medium text-danger">{{ session('error') }}</p>
            </div>
          @endif

          <form action="{{ route('admin.authenticate') }}" method="POST" class="mt-6 grid gap-5">
            @csrf

            <div>
              <label for="email" class="mb-1.5 block font-body text-[13px] font-bold text-ink">E-posta</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                     class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                     placeholder="admin@firma.com">
              @error('email') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="password" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Şifre</label>
              <div class="relative">
                <input type="password" id="password" name="password" required autocomplete="current-password"
                       class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 pr-11 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                       placeholder="••••••••">
                <button type="button" data-toggle-password aria-label="Şifreyi göster"
                        class="absolute right-0 top-0 flex h-full w-11 items-center justify-center text-muted transition-colors hover:text-ink">
                  <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2.036 12.593a1 1 0 010-.186C3.54 7.442 7.674 4.5 12 4.5c4.326 0 8.46 2.942 9.964 6.907a1 1 0 010 .186C20.46 16.558 16.326 19.5 12 19.5c-4.326 0-8.46-2.942-9.964-6.907z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
              </div>
              @error('password') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between gap-3">
              <label class="flex cursor-pointer items-center gap-2 font-body text-[13px] text-ink">
                <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="h-4 w-4 accent-accent">
                Beni hatırla
              </label>
            </div>

            <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
              Giriş Yap
            </button>
          </form>
        </div>

        <p class="mt-6 text-center font-body text-[13px] text-muted">
          <a href="{{ route('index') }}" class="font-semibold text-accent transition-colors hover:text-accent-dark">← Siteye dön</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    document.querySelector('[data-toggle-password]')?.addEventListener('click', function () {
      const input = document.getElementById('password');
      if (!input) return;
      input.type = input.type === 'password' ? 'text' : 'password';
    });
  </script>
</body>
</html>
