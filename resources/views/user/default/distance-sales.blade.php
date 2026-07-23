@extends('user.layout')
@section('title', 'Mesafeli Satış Sözleşmesi')

@section('content')
  <x-static-page title="Mesafeli Satış Sözleşmesi" lead="6502 sayılı Tüketicinin Korunması Hakkında Kanun kapsamında mesafeli satış sözleşmesi.">
    <h2>Taraflar</h2>
    <p>İşbu sözleşme, PureMatPrint (satıcı) ile web sitesi üzerinden alışveriş yapan tüketici (alıcı) arasında elektronik ortamda kurulmuştur.</p>
    <h2>Konu</h2>
    <p>Alıcının, satıcıya ait internet sitesinden elektronik ortamda sipariş verdiği ürün/hizmetin satışı ve teslimi ile ilgili tarafların hak ve yükümlülüklerini düzenler.</p>
    <h2>Cayma hakkı</h2>
    <p>Alıcı, ürünü teslim aldığı tarihten itibaren 14 gün içinde herhangi bir gerekçe göstermeksizin cayma hakkını kullanabilir. Cayma hakkının kullanılması için satıcıya yazılı bildirimde bulunulması gerekir.</p>
    <h2>Teslimat</h2>
    <p>Ürünler, sipariş onayı sonrasında belirtilen süre içinde alıcının adresine teslim edilir. Teslimat koşulları <a href="{{ route('shippingInfo') }}">Kargo & Teslimat</a> sayfasında detaylandırılmıştır.</p>
  </x-static-page>
@endsection
