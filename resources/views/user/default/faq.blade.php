@extends('user.layout')
@section('title', 'Sık Sorulan Sorular')

@section('content')
  <main class="py-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-8 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]">/</span>
        <span class="text-ink">Sık Sorulan Sorular</span>
      </nav>

      <header class="mb-10 max-w-[720px]">
        <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] text-ink normal-case mb-4">Sık Sorulan Sorular</h1>
        <p class="m-0 text-muted text-base leading-relaxed">Sipariş, üretim, teslimat ve ödeme süreçleri hakkında en çok merak edilen soruların yanıtları.</p>
      </header>

      @if ($faqGroups->isEmpty())
        <div class="border-[3px] border-ink bg-surface p-8 shadow-brutal-sm text-center">
          <p class="m-0 font-body text-sm text-muted">Henüz soru eklenmemiş. Kısa süre içinde burayı güncelleyeceğiz.</p>
        </div>
      @else
        <div class="grid gap-10">
          @foreach ($faqGroups as $faqGroup)
            <section class="min-w-0" aria-labelledby="faq-group-{{ $faqGroup->id }}">
              <h2 id="faq-group-{{ $faqGroup->id }}" class="mb-4 font-heading text-[clamp(1.25rem,2.5vw,1.75rem)] font-bold text-ink normal-case">
                {{ $faqGroup->title }}
              </h2>

              <div class="border-[3px] border-ink bg-surface shadow-brutal-sm divide-y-[3px] divide-ink">
                @foreach ($faqGroup->faqs as $faq)
                  <details class="group/faq">
                    <summary class="flex items-center justify-between gap-4 px-5 py-4 cursor-pointer list-none font-body text-[15px] font-bold text-ink [&::-webkit-details-marker]:hidden">
                      <span>{{ $faq->title }}</span>
                      <svg class="shrink-0 transition-transform group-open/faq:rotate-180" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                    </summary>
                    <div class="px-5 pb-5 text-sm leading-relaxed text-muted whitespace-pre-line">{{ $faq->content }}</div>
                  </details>
                @endforeach
              </div>
            </section>
          @endforeach
        </div>
      @endif

      <div class="mt-12 border-[3px] border-ink bg-bg p-6 text-center shadow-brutal-sm">
        <p class="m-0 mb-4 font-body text-sm text-muted">Aradığınız cevabı bulamadınız mı?</p>
        <a href="{{ route('contact') }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">
          Bize Ulaşın
        </a>
      </div>
    </div>
  </main>
@endsection
