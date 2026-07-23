@extends('user.layout')
@section('title', 'Kargo & Teslimat')

@section('content')
  <x-static-page title="Kargo & Teslimat" lead="Siparişlerinizin teslimat süreci ve kargo koşulları hakkında bilgi.">
    <p>{{ $setting->shippingDetailText() }}</p>
    <h2>Genel bilgiler</h2>
    <ul>
      <li>Siparişleriniz onaylandıktan sonra hazırlık sürecine alınır.</li>
      <li>Kargo takip bilgisi e-posta ile paylaşılır.</li>
      <li>Teslimat süresi bölge ve kargo firmasına göre değişiklik gösterebilir.</li>
    </ul>
    <p>Ek sorularınız için <a href="{{ route('contact') }}">iletişim</a> sayfamızdan bize ulaşabilirsiniz.</p>
  </x-static-page>
@endsection
