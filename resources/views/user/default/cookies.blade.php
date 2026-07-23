@extends('user.layout')
@section('title', 'Çerez Politikası')

@section('content')
  <x-static-page title="Çerez Politikası" lead="Web sitemizde kullanılan çerezler ve tercihleriniz hakkında bilgi.">
    <p>Çerezler, site deneyiminizi iyileştirmek ve temel işlevleri sağlamak amacıyla tarayıcınıza kaydedilen küçük metin dosyalarıdır.</p>
    <h2>Kullandığımız çerez türleri</h2>
    <ul>
      <li><strong>Zorunlu çerezler:</strong> Oturum ve güvenlik işlevleri için gereklidir.</li>
      <li><strong>İşlevsel çerezler:</strong> Tercihlerinizi hatırlamamıza yardımcı olur.</li>
      <li><strong>Analitik çerezler:</strong> Site kullanımını anonim olarak analiz etmemizi sağlar.</li>
    </ul>
    <h2>Çerez tercihleri</h2>
    <p>Tarayıcı ayarlarınızdan çerezleri silebilir veya engelleyebilirsiniz. Zorunlu çerezlerin devre dışı bırakılması site işlevlerini etkileyebilir.</p>
  </x-static-page>
@endsection
