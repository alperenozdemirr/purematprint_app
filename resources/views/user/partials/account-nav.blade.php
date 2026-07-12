@php
  $navUser = auth()->user();
  $initials = collect(preg_split('/\s+/', trim($navUser->name)))
      ->filter()
      ->take(2)
      ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
      ->implode('');
@endphp
<aside class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="account-nav">
  <div class="flex items-center gap-3.5 px-[18px] py-5 border-b-[3px] border-ink bg-bg" data-i5="account-nav__user">
    <span class="w-12 h-12 flex items-center justify-center font-body text-sm font-bold uppercase tracking-[0.04em] bg-accent text-on-dark border-[3px] border-ink shrink-0" aria-hidden="true" data-i5="account-nav__avatar">{{ $initials }}</span>
    <div>
      <p class="font-body text-sm font-bold uppercase tracking-[0.02em] mb-0.5" data-i5="account-nav__name">{{ $navUser->name }}</p>
      <p class="text-xs text-muted break-all" data-i5="account-nav__email">{{ $navUser->email }}</p>
    </div>
  </div>
  <nav class="grid p-2" aria-label="Hesap menüsü" data-i5="account-nav__links">
    <a href="{{ route('account') }}" class="flex items-center gap-2.5 px-3.5 py-3 font-body text-xs font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&_svg]:shrink-0 [&_svg]:opacity-70 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active_svg]:opacity-100 {{ ($activeNav ?? '') === 'account' ? 'is-active' : '' }}" data-i5="account-nav__link">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
      Profil Bilgilerim
    </a>
    <a href="{{ route('orderList') }}" class="flex items-center gap-2.5 px-3.5 py-3 font-body text-xs font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&_svg]:shrink-0 [&_svg]:opacity-70 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active_svg]:opacity-100 {{ ($activeNav ?? '') === 'orders' ? 'is-active' : '' }}" data-i5="account-nav__link">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
      Siparişlerim
    </a>
    <a href="{{ route('addressList') }}" class="flex items-center gap-2.5 px-3.5 py-3 font-body text-xs font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&_svg]:shrink-0 [&_svg]:opacity-70 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active_svg]:opacity-100 {{ ($activeNav ?? '') === 'addresses' ? 'is-active' : '' }}" data-i5="account-nav__link">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
      Adreslerim
    </a>
  </nav>
  <form action="{{ route('logout') }}" method="post" class="mx-2 mb-2">
    @csrf
    <button type="submit" class="block w-full px-3.5 py-3 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-center text-announce border-2 border-announce transition-[background,color] hover:bg-announce hover:text-on-dark" data-i5="account-nav__logout">Çıkış Yap</button>
  </form>
</aside>
