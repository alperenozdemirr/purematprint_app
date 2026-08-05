@extends('user.layout')
@section('title', 'Kargo & Teslimat')

@section('content')
  <x-static-page title="Kargo & Teslimat" lead="Türkiye içi gönderiler Aras Kargo ile yapılır.">
    <p>Bu sayfa özet bilgilendirme amaçlıdır. Ayrıntılı teslimat, iptal, iade ve cayma koşulları için <a href="{{ route('agreements') }}#teslimat-iptal-iade-cayma">Teslimat, İptal, İade ve Cayma Politikası</a> metnini inceleyiniz.</p>

    <h2>Teslimat</h2>
    <p>Bu politika yalnızca Türkiye içindeki siparişler için geçerlidir. Anlaşmalı teslimat ve iade taşıyıcısı Aras Kargo’dur.</p>
    <p>Ürüne özel hazırlık ve tahminî teslim süresi ürün sayfasında, sipariş özetinde veya yazılı teklifte gösterilir. Kişiye özel üretimde süre, ödeme ve üretim dosyası onayı tamamlandıktan sonra başlar.</p>
    <p>Standart malların teslimi mevzuattaki azami otuz günlük süreyi aşamaz. Tüketicinin istekleri veya kişisel ihtiyaçları doğrultusunda hazırlanan ürünlere ilişkin mevzuat istisnası saklıdır.</p>

    <h2>İade taşıyıcısı</h2>
    <p>Cayma iadelerinde Aras Kargo kullanılır. Aras Kargo ile yapılan cayma iadelerinin kargo masrafı Satıcı tarafından karşılanır.</p>

    <p>Ek sorularınız için <a href="{{ route('contact') }}">iletişim</a> sayfamızdan bize ulaşabilirsiniz.</p>
  </x-static-page>
@endsection
