@extends('user.layout')
@section('title', 'Hakkımızda')
@section('metaDescription', 'PureMatPrint hakkında: baskı, tabela ve kurumsal kimlik üretiminde deneyim, kalite anlayışı ve müşteri odaklı hizmet yaklaşımımızı keşfedin.')
@section('canonicalUrl', route('about'))
@section('content')
  <x-static-page title="Hakkımızda" lead="Puremat Print — Fikrinizi görünür, kullanılabilir ve üretilebilir bir çözüme dönüştürür.">
    <p>Puremat Print, GENÇ PRINT REKLAM ANONİM ŞİRKETİ’nin baskı, reklam ve özel üretim markasıdır.</p>

    <p>İstanbul’daki üretim altyapımızla tabela, pleksi uygulamalar, yönlendirme ürünleri, menü, etiket, sticker, kartvizit ve iç/dış mekân reklam çözümleri hazırlıyoruz.</p>

    <p>Baskıya hazır dosyalarla çalışabildiğimiz gibi ihtiyaca göre tasarım desteği de sunuyoruz. Ürün ölçüsünü, kullanım alanını, adedi ve teslimat beklentisini değerlendiriyor; kişiye özel üretimlerde müşterinin yazılı veya sonradan doğrulanabilir dijital onayı üzerinden ilerliyoruz.</p>

    <p>Standart ürünlerden kurumsal ve kişiye özel uygulamalara kadar her siparişte açık iletişime, kontrollü üretime ve özenli teslimata önem veriyoruz.</p>

    <p><strong>Puremat Print — Fikrinizi görünür, kullanılabilir ve üretilebilir bir çözüme dönüştürür.</strong></p>

    <p>İletişim ve şirket bilgileri için <a href="{{ route('contact') }}">iletişim</a> sayfamızı ziyaret edebilir; yasal metinler için <a href="{{ route('agreements') }}">sözleşmeler</a> sayfamıza bakabilirsiniz.</p>
  </x-static-page>
@endsection
