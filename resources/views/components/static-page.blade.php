@props(['title', 'lead' => null])

<main class="py-8 pb-20">
  <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
    <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
      <a href="{{ route('index') }}">Anasayfa</a>
      <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
      <span>{{ $title }}</span>
    </nav>

    <div class="mb-10">
      <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] normal-case">{{ $title }}</h1>
      @if ($lead)
        <p class="mt-2.5 text-[15px] text-muted leading-[1.6] max-w-[640px]">{{ $lead }}</p>
      @endif
    </div>

    <div class="border-[3px] border-ink bg-surface shadow-brutal-sm p-6 md:p-8 text-[15px] leading-[1.75] text-muted [&_h2]:font-heading [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:text-ink [&_h2]:mt-8 [&_h2]:mb-3 [&_h2:first-child]:mt-0 [&_p+p]:mt-4 [&_ul]:mt-4 [&_ul]:space-y-2 [&_ul]:list-disc [&_ul]:pl-5 [&_a]:text-accent [&_a]:font-semibold [&_a]:underline [&_a]:underline-offset-[3px] hover:[&_a]:text-accent-dark">
      {{ $slot }}
    </div>
  </div>
</main>
