@extends('user.layout')
@section('title','Adreslerim')
@section('content')
@php
  $authUser = auth()->user();
@endphp
<main id="addresses-root" class="pt-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Adreslerim</span>
      </nav>

      @if (session('success'))
      <div class="mb-5 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-ink" role="alert">{{ session('success') }}</div>
      @endif
      @if (session('error'))
      <div class="mb-5 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
      @endif

      <div class="flex flex-wrap items-end justify-between gap-4 mb-8 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="addresses-page__head">
        <div>
          <h1>Adreslerim</h1>
          <p class="mt-2.5 text-sm text-muted font-semibold" data-i5="addresses-page__sub">{{ $authUser->email }} · {{ $addresses->count() }} kayıtlı adres</p>
        </div>
        <a data-i5="btn--fill" href="{{ route('addressCreatePage') }}" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">Yeni Adres Ekle</a>
      </div>

      <div class="grid gap-4 min-[640px]:grid-cols-2 min-[1024px]:grid-cols-3" id="addresses-grid" data-i5="addresses-grid">
        @forelse ($addresses as $address)
        <article class="border-[3px] border-ink shadow-brutal-sm bg-surface flex flex-col min-h-full transition-shadow hover:shadow-brutal" data-i5="address-card" data-address-id="{{ $address->id }}">
          <div class="flex items-center justify-between gap-2 px-4 py-3.5 border-b-[3px] border-ink bg-bg" data-i5="address-card__head">
            <span class="font-body text-[11px] font-bold uppercase tracking-[0.06em]" data-i5="address-card__label">{{ $address->title }}</span>
          </div>
          <div class="p-4 flex-1 text-sm leading-[1.6] text-muted" data-i5="address-card__body">
            <p class="font-bold text-ink mb-1.5" data-i5="address-card__name">{{ $authUser->name }}</p>
            <p>{{ $address->content }}</p>
            <p>{{ $address->county?->name }}, {{ $address->city?->name }}</p>
            @if ($authUser->phone)
            <p class="mt-2.5 text-[13px] font-semibold text-ink" data-i5="address-card__phone">{{ $authUser->phone }}</p>
            @endif
          </div>
          <div class="flex flex-wrap gap-2 px-4 py-3 border-t-[3px] border-ink" data-i5="address-card__actions">
            <a href="{{ route('addressEditPage', $address->id) }}" class="font-body text-[10px] font-bold uppercase tracking-[0.04em] px-3 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface transition-colors inline-flex items-center justify-center hover:bg-hover" data-i5="address-card__btn">Düzenle</a>
            <a href="{{ route('addressDelete', $address->id) }}" class="font-body text-[10px] font-bold uppercase tracking-[0.04em] px-3 py-2 border-[3px] border-ink shadow-brutal-sm bg-surface transition-colors inline-flex items-center justify-center hover:bg-hover text-announce hover:bg-[rgba(182,29,15,0.08)]" data-i5="address-card__btn--danger" onclick="return confirm('Bu adresi silmek istediğinize emin misiniz?')">Sil</a>
          </div>
        </article>
        @empty
        <div class="min-[640px]:col-span-2 min-[1024px]:col-span-3 border-[3px] border-ink shadow-brutal-sm bg-surface p-10 text-center">
          <p class="text-muted mb-4">Henüz kayıtlı adresiniz yok.</p>
          <a href="{{ route('addressCreatePage') }}" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover">İlk Adresinizi Ekleyin</a>
        </div>
        @endforelse

        @if ($addresses->isNotEmpty())
        <a data-i5="address-card--add" href="{{ route('addressCreatePage') }}" class="border-[3px] border-ink shadow-brutal-sm bg-surface flex flex-col min-h-full transition-shadow hover:shadow-brutal border-dashed shadow-none bg-bg min-h-[220px] items-center justify-center cursor-pointer transition-[background,border-color] no-underline text-inherit hover:bg-hover hover:border-accent [&_svg]:text-muted [&_svg]:mb-2.5 [&_span]:font-body [&_span]:text-xs [&_span]:font-bold [&_span]:uppercase [&_span]:tracking-[0.04em] [&_span]:text-muted">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 4.5v15m7.5-7.5h-15"/></svg>
          <span>Yeni Adres Ekle</span>
        </a>
        @endif
      </div>
    </div>
  </main>
@endsection
