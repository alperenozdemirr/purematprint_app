@if ($homepageFaqs->isNotEmpty())
  <section class="pt-16 border-t-[3px] border-ink mt-16" id="sss" data-i5="pdp-faq">
    <div class="w-full max-w-[760px] mx-auto">
      <div class="mb-8 text-center">
        <h2 class="font-heading text-[clamp(1.5rem,3vw,2rem)] font-bold leading-tight tracking-[-0.02em] text-ink normal-case mb-3">Sık Sorulan Sorular</h2>
        <p class="m-0 text-[15px] leading-relaxed text-muted">Sipariş ve teslimat süreci hakkında hızlı yanıtlar.</p>
      </div>

      <div class="border-t border-ink/12">
        @foreach ($homepageFaqs as $faq)
          <details class="group/faq border-b border-ink/12">
            <summary class="flex items-center justify-between gap-4 py-5 list-none cursor-pointer text-ink font-heading text-[clamp(1rem,2.2vw,1.25rem)] font-bold leading-tight tracking-tight [&::-webkit-details-marker]:hidden">
              <span>{{ $faq->title }}</span>
              <svg class="shrink-0 transition-transform group-open/faq:rotate-180" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </summary>
            <div class="pb-6 text-[15px] leading-relaxed text-muted whitespace-pre-line">{{ $faq->content }}</div>
          </details>
        @endforeach
      </div>

      <div class="mt-8 text-center">
        <a href="{{ route('faq') }}"
           class="inline-flex items-center gap-2 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-accent transition-colors hover:text-accent-dark">
          Tüm soruları görüntüle
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </section>
@endif
