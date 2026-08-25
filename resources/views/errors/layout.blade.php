<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ route('seo.favicon') }}">
  <title>@yield('title') — PureMatPrint</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Petrona:wght@500;600;700&display=swap">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            bg: '#faf6ee',
            surface: '#fffdf8',
            ink: '#1a1a1a',
            muted: '#5e5a54',
            accent: '#354e9c',
            'accent-dark': '#283c78',
            action: '#5a544e',
            'action-hover': '#6b645c',
            'on-dark': '#faf6ee',
            announce: '#b61d0f',
          },
          fontFamily: {
            heading: ['Petrona', 'Georgia', 'Times New Roman', 'serif'],
            body: ['IBM Plex Sans', 'system-ui', '-apple-system', 'sans-serif'],
          },
          boxShadow: {
            brutal: '6px 6px 0 rgba(26, 26, 26, 0.68)',
            'brutal-sm': '4px 4px 0 rgba(26, 26, 26, 0.68)',
          },
        },
      },
    };
  </script>
</head>
<body class="min-h-screen bg-bg font-body text-ink antialiased">
  @php
    $isAdmin = request()->is('admin') || request()->is('admin/*');
    $homeUrl = $isAdmin ? route('admin.index') : route('index');
    $homeLabel = $isAdmin ? 'Admin Paneline Dön' : 'Anasayfaya Dön';
  @endphp

  <main class="flex min-h-screen items-center justify-center px-5 py-16">
    <div class="w-full max-w-xl">
      <div class="border-[3px] border-ink bg-surface shadow-brutal">
        <div class="border-b-[3px] border-ink bg-accent px-6 py-5 text-on-dark">
          <p class="font-body text-[11px] font-bold uppercase tracking-[0.12em] opacity-90">PureMatPrint</p>
          <p class="mt-2 font-heading text-[56px] font-bold leading-none tracking-[-0.04em]">@yield('code')</p>
        </div>

        <div class="px-6 py-8">
          <h1 class="font-heading text-[clamp(1.5rem,4vw,2rem)] font-semibold leading-tight tracking-[-0.02em]">
            @yield('title')
          </h1>
          <p class="mt-4 text-[15px] leading-relaxed text-muted">
            @yield('message')
          </p>

          @hasSection('hint')
            <p class="mt-3 text-[13px] leading-relaxed text-muted/90">
              @yield('hint')
            </p>
          @endif

          <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ $homeUrl }}"
               class="inline-flex items-center justify-center border-[3px] border-ink bg-action px-5 py-3 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-on-dark shadow-brutal-sm transition-transform hover:-translate-x-0.5 hover:-translate-y-0.5">
              {{ $homeLabel }}
            </a>
            @unless($isAdmin)
              <a href="{{ route('shops') }}"
                 class="inline-flex items-center justify-center border-[3px] border-ink bg-surface px-5 py-3 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-ink shadow-brutal-sm transition-transform hover:-translate-x-0.5 hover:-translate-y-0.5">
                Mağazaya Git
              </a>
            @endunless
            <button type="button" onclick="history.back()"
                    class="inline-flex items-center justify-center border-[3px] border-ink bg-surface px-5 py-3 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-ink shadow-brutal-sm transition-transform hover:-translate-x-0.5 hover:-translate-y-0.5">
              Geri Dön
            </button>
          </div>
        </div>
      </div>

      <p class="mt-6 text-center font-body text-[12px] text-muted">
        © {{ date('Y') }} PureMatPrint — Baskı & Tabela Stüdyosu
      </p>
    </div>
  </main>
</body>
</html>
