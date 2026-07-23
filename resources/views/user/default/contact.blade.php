@extends('user.layout')
@section('title', 'İletişim')

@section('content')
  <x-static-page title="İletişim" lead="Sorularınız, teklif talepleriniz ve sipariş desteği için bize ulaşın.">
    <div class="grid gap-6 md:grid-cols-2 not-prose">
      @if ($setting->email)
        <div>
          <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-ink mb-1">E-posta</p>
          <a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a>
        </div>
      @endif
      @if ($setting->mobile_phone)
        <div>
          <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-ink mb-1">Cep Telefonu</p>
          <a href="tel:{{ preg_replace('/\s+/', '', $setting->mobile_phone) }}">{{ $setting->mobile_phone }}</a>
        </div>
      @endif
      @if ($setting->business_phone)
        <div>
          <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-ink mb-1">İş Telefonu</p>
          <a href="tel:{{ preg_replace('/\s+/', '', $setting->business_phone) }}">{{ $setting->business_phone }}</a>
        </div>
      @endif
      @if ($setting->whatsappLink())
        <div>
          <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-ink mb-1">WhatsApp</p>
          <a href="{{ $setting->whatsappLink('Merhaba, bilgi almak istiyorum.') }}" target="_blank" rel="noopener noreferrer">WhatsApp ile yazın</a>
        </div>
      @endif
      @if ($setting->address)
        <div class="md:col-span-2">
          <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-ink mb-1">Adres</p>
          <p class="whitespace-pre-line">{{ $setting->address }}</p>
        </div>
      @endif
    </div>

    @if ($setting->instagram_url || $setting->twitter_url || $setting->facebook_url)
      <h2>Sosyal Medya</h2>
      <ul>
        @if ($setting->instagram_url)
          <li><a href="{{ $setting->instagram_url }}" target="_blank" rel="noopener noreferrer">Instagram</a></li>
        @endif
        @if ($setting->twitter_url)
          <li><a href="{{ $setting->twitter_url }}" target="_blank" rel="noopener noreferrer">Twitter / X</a></li>
        @endif
        @if ($setting->facebook_url)
          <li><a href="{{ $setting->facebook_url }}" target="_blank" rel="noopener noreferrer">Facebook</a></li>
        @endif
      </ul>
    @endif
  </x-static-page>
@endsection
