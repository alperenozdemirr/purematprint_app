@extends('admin.layout')
@section('title', 'Kullanıcılar')
@section('page_title', 'Kullanıcılar')
@section('breadcrumb', 'Sistem / Kullanıcılar')

@section('content')
  <div class="mb-6">
    <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Kullanıcı Listesi</h2>
    <p class="font-body text-[13px] text-muted">Toplam <span class="font-bold text-ink">{{ $users->total() }}</span> kullanıcı</p>
  </div>

  <form action="{{ route('admin.userList') }}" method="get" class="mb-6 rounded-xl bg-surface p-4 shadow-card">
    <div class="grid grid-cols-1 gap-3 md:grid-cols-[1fr_auto_auto_auto]">
      <div class="flex items-center overflow-hidden rounded-lg border border-ink/10 bg-cream">
        <span class="flex w-11 shrink-0 items-center justify-center text-muted">
          <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="7.8"/><path stroke-linecap="square" d="m17 17 4.5 4.5"/></svg>
        </span>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Ad, e-posta veya telefon ara..." class="w-full border-0 bg-transparent px-3 py-2.5 font-body text-[14px] text-ink outline-none placeholder:text-muted">
      </div>
      <select name="type" class="rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[14px] font-medium text-ink outline-none focus:border-accent">
        <option value="">Tüm Roller</option>
        @foreach ($userTypes as $userType)
          <option value="{{ $userType->value }}" @selected(request('type') === $userType->value)>{{ $userType->label() }}</option>
        @endforeach
      </select>
      <select name="status" class="rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[14px] font-medium text-ink outline-none focus:border-accent">
        <option value="">Tüm Durumlar</option>
        @foreach ($userStatuses as $userStatus)
          <option value="{{ $userStatus->value }}" @selected(request('status') === $userStatus->value)>{{ $userStatus->label() }}</option>
        @endforeach
      </select>
      <button type="submit" class="rounded-lg bg-ink px-5 py-2.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-action">Filtrele</button>
    </div>
  </form>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[900px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th>Kullanıcı</th>
            <th>Telefon</th>
            <th>Rol</th>
            <th>Durum</th>
            <th>Sipariş</th>
            <th>Kayıt</th>
            <th class="text-right">İşlem</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($users as $user)
            <tr class="transition-colors hover:bg-hover/60">
              <td>
                <p class="font-body text-[14px] font-bold text-ink">{{ $user->name }}</p>
                <p class="font-body text-[12px] text-muted">{{ $user->email }}</p>
              </td>
              <td class="font-body text-[13px] text-ink">{{ $user->phone }}</td>
              <td>
                <span class="inline-flex rounded-md px-2.5 py-1 font-body text-[11px] font-bold {{ $user->type?->value === 'admin' ? 'bg-accent/10 text-accent' : 'bg-cream text-ink' }}">
                  {{ $user->type?->label() }}
                </span>
              </td>
              <td>
                @if ($user->status?->value === 'active')
                  <span class="inline-flex items-center gap-1.5 rounded-md bg-success/10 px-2.5 py-1 font-body text-[11px] font-bold text-success">
                    <span class="h-1.5 w-1.5 rounded-full bg-success"></span>{{ $user->status->label() }}
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 rounded-md bg-hover px-2.5 py-1 font-body text-[11px] font-bold text-muted">
                    <span class="h-1.5 w-1.5 rounded-full bg-muted"></span>{{ $user->status?->label() }}
                  </span>
                @endif
              </td>
              <td class="font-body text-[13px] font-semibold text-ink">{{ $user->orders_count }}</td>
              <td class="font-body text-[13px] text-muted">{{ $user->created_at?->format('d.m.Y') }}</td>
              <td>
                <div class="flex justify-end">
                  <a href="{{ route('admin.userDetailPage', $user->id) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-cream px-3 py-2 font-body text-[12px] font-bold text-ink transition-colors hover:bg-accent hover:text-on-dark">
                    Detay
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-10 text-center font-body text-[14px] text-muted">Kullanıcı bulunamadı.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($users->hasPages())
      <div class="flex flex-col items-center justify-between gap-3 border-t border-ink/10 px-4 py-3.5 sm:flex-row">
        <p class="font-body text-[13px] text-muted">{{ $users->firstItem() }}–{{ $users->lastItem() }} / {{ $users->total() }} gösteriliyor</p>
        <div>{{ $users->links() }}</div>
      </div>
    @endif
  </div>
@endsection
