@extends('user.layout')
@section('title','Bakım Modu')
@section('content')
<main class="py-20">
  <div class="w-full max-w-site mx-auto px-5 lg:px-8">
    <div class="mx-auto max-w-xl border-[3px] border-ink bg-surface p-10 text-center shadow-brutal">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.12em] text-accent mb-3">PureMatPrint</p>
      <h1 class="font-heading text-page-title font-semibold leading-tight mb-4">Site Bakımda</h1>
      <p class="text-[15px] leading-relaxed text-muted mb-8">
        Şu anda kısa süreli bakım çalışması yapıyoruz. Lütfen daha sonra tekrar ziyaret edin.
      </p>
      <a href="{{ route('index') }}" class="inline-flex items-center justify-center px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover">
        Yenile
      </a>
    </div>
  </div>
</main>
@endsection
