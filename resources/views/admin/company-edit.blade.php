@extends('admin.layout')
@section('title', 'Referans Firma Düzenle')
@section('page_title', 'Referans Firma Düzenle')
@section('breadcrumb', 'İçerik / Referans Firmalar / Düzenle')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.companyList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">{{ $company->name }}</h2>
      <p class="font-body text-[13px] text-muted">Sıra: {{ $company->number }}</p>
    </div>
  </div>

  <form action="{{ route('admin.companyUpdate') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $company->id }}">

    <div class="mx-auto max-w-2xl overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Firma Bilgileri</h3>
      </div>
      <div class="grid grid-cols-1 gap-5 p-5">
        <div>
          <label for="name" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Firma Adı <span class="text-danger">*</span></label>
          <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" required
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('name') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="flex flex-col gap-3 border-t border-ink/10 p-5 sm:flex-row">
        <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
          Değişiklikleri Kaydet
        </button>
        <a href="{{ route('admin.companyList') }}" class="inline-flex flex-1 items-center justify-center rounded-lg bg-cream px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-ink transition-colors hover:bg-hover">
          İptal
        </a>
      </div>
    </div>
  </form>
@endsection
