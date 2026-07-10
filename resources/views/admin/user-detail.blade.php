@extends('admin.layout')
@section('title', $user->name)
@section('page_title', 'Kullanıcı Detayı')
@section('breadcrumb', 'Sistem / Kullanıcılar / ' . $user->name)

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.userList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">{{ $user->name }}</h2>
      <p class="font-body text-[13px] text-muted">{{ $user->email }} · {{ $user->created_at?->format('d.m.Y') }} tarihinde kayıt oldu</p>
    </div>
  </div>

  <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-xl bg-surface p-5 shadow-card">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Toplam Sipariş</p>
      <p class="mt-2 font-heading text-[28px] font-bold text-ink">{{ $user->orders_count }}</p>
    </div>
    <div class="rounded-xl bg-surface p-5 shadow-card">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Toplam Harcama</p>
      <p class="mt-2 font-heading text-[28px] font-bold text-ink">{{ number_format((float) $totalSpent, 2, ',', '.') }}₺</p>
    </div>
    <div class="rounded-xl bg-surface p-5 shadow-card">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Kayıtlı Adres</p>
      <p class="mt-2 font-heading text-[28px] font-bold text-ink">{{ $user->addresses->count() }}</p>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_380px]">
    <div class="flex flex-col gap-6">
      {{-- Adresler --}}
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Kayıtlı Adresler</h3>
        </div>
        <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
          @forelse ($user->addresses as $address)
            <div class="rounded-lg border border-ink/10 bg-cream/40 p-4">
              <p class="font-body text-[14px] font-bold text-ink">{{ $address->title }}</p>
              <p class="mt-1 font-body text-[13px] leading-relaxed text-ink">{{ $address->content }}</p>
              <p class="mt-2 font-body text-[12px] text-muted">{{ $address->county?->name }} / {{ $address->city?->name }}</p>
            </div>
          @empty
            <p class="col-span-full px-1 py-4 text-center font-body text-[14px] text-muted">Kayıtlı adres bulunmuyor.</p>
          @endforelse
        </div>
      </section>

      {{-- Siparişler --}}
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Son Siparişler</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full min-w-[640px] border-collapse text-left">
            <thead>
              <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
                <th>Kod</th>
                <th>Tarih</th>
                <th>Tutar</th>
                <th>Durum</th>
                <th class="text-right">İşlem</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3">
              @forelse ($user->orders as $order)
                @php
                  $statusClass = match ($order->status?->value) {
                    'preparing' => 'bg-accent/10 text-accent',
                    'shipped' => 'bg-ink/10 text-ink',
                    'completed' => 'bg-success/10 text-success',
                    'cancelled' => 'bg-danger/10 text-danger',
                    default => 'bg-hover text-muted',
                  };
                @endphp
                <tr>
                  <td class="font-body text-[14px] font-bold text-ink">{{ $order->code }}</td>
                  <td class="font-body text-[13px] text-muted">{{ $order->created_at?->format('d.m.Y') }}</td>
                  <td class="font-body text-[13px] font-bold text-ink">{{ number_format((float) $order->total, 2, ',', '.') }}₺</td>
                  <td>
                    <span class="inline-flex rounded-md px-2 py-0.5 font-body text-[11px] font-bold {{ $statusClass }}">{{ $order->status?->label() }}</span>
                  </td>
                  <td class="text-right">
                    <a href="{{ route('admin.orderDetailPage', $order->code) }}" class="font-body text-[12px] font-bold text-accent hover:underline">Görüntüle</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-4 py-8 text-center font-body text-[14px] text-muted">Henüz sipariş yok.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>
    </div>

  <form action="{{ route('admin.userUpdate') }}" method="POST" class="overflow-hidden rounded-xl bg-surface shadow-card xl:sticky xl:top-6 xl:self-start">
      @csrf
      <input type="hidden" name="id" value="{{ $user->id }}">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Kullanıcı Bilgileri</h3>
      </div>
      <div class="space-y-4 p-5">
        <div>
          <label for="name" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ad Soyad</label>
          <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent">
          @error('name') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="mb-1.5 block font-body text-[13px] font-bold text-ink">E-posta</label>
          <input type="email" value="{{ $user->email }}" disabled
                 class="w-full cursor-not-allowed rounded-lg border border-ink/10 bg-cream/60 px-3.5 py-2.5 font-body text-[14px] text-muted outline-none">
          <p class="mt-1.5 font-body text-[12px] text-muted">E-posta admin panelden güncellenemez.</p>
        </div>

        <div>
          <label for="phone" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Telefon</label>
          <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent">
          @error('phone') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="type" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Rol</label>
          <select id="type" name="type" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] font-medium text-ink outline-none focus:border-accent">
            @foreach ($userTypes as $userType)
              <option value="{{ $userType->value }}" @selected(old('type', $user->type?->value) === $userType->value)>{{ $userType->label() }}</option>
            @endforeach
          </select>
          @error('type') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="status" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Hesap Durumu</label>
          <select id="status" name="status" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] font-medium text-ink outline-none focus:border-accent">
            @foreach ($userStatuses as $userStatus)
              <option value="{{ $userStatus->value }}" @selected(old('status', $user->status?->value) === $userStatus->value)>{{ $userStatus->label() }}</option>
            @endforeach
          </select>
          @error('status') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="rounded-lg border border-ink/10 bg-cream/40 px-4 py-3">
          <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Sözleşme Onayları</p>
          <div class="mt-2 space-y-1 font-body text-[13px] text-ink">
            <p>KVKK: {{ $user->kvkk_confirm ? 'Onaylı' : 'Onaysız' }}</p>
            <p>Gizlilik: {{ $user->privacy_confirm ? 'Onaylı' : 'Onaysız' }}</p>
            <p>Mesafeli Satış: {{ $user->distance_sales_contract_confirm ? 'Onaylı' : 'Onaysız' }}</p>
          </div>
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
          Kullanıcıyı Güncelle
        </button>
      </div>
    </form>
  </div>
@endsection
