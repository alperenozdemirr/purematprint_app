@extends('user.layout')
@section('title', 'Hakkımızda')

@section('content')
  <x-static-page title="Hakkımızda" lead="PureMatPrint — baskı, tabela ve kurumsal kimlik alanında cesur tasarım ve kusursuz üretim.">
    <p>PureMatPrint, markaların görünürlüğünü güçlendiren baskı ve tabela çözümleri sunan bir stüdyodur. Tasarımdan üretime kadar tüm süreci tek çatı altında yönetiyoruz.</p>
    <p>Müşterilerimize hızlı teslimat, kaliteli malzeme ve şeffaf fiyatlandırma ile güvenilir bir alışveriş deneyimi sunmayı hedefliyoruz.</p>
    <h2>Neler yapıyoruz?</h2>
    <ul>
      <li>Tabela ve yönlendirme sistemleri</li>
      <li>Kurumsal baskı ve promosyon ürünleri</li>
      <li>Özel tasarım ve marka kimliği uygulamaları</li>
    </ul>
    <p>Sorularınız için <a href="{{ route('contact') }}">iletişim</a> sayfamızdan bize ulaşabilirsiniz.</p>
  </x-static-page>
@endsection
