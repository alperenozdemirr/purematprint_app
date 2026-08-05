@extends('user.layout')
@section('title', 'Çerez Politikası')

@section('content')
  <x-static-page title="Çerez Politikası" lead="Puremat Print; sitenin güvenli çalışması, sepet ve ödeme akışının yürütülmesi, tercihlerin saklanması ve yalnızca izin verilmesi hâlinde site kullanımı ile reklam performansının ölçülmesi amacıyla çerez ve benzeri teknolojiler kullanır.">
    <h2>Çerez kategorileri</h2>
    <ul>
      <li><strong>Kesinlikle gerekli çerezler:</strong> Sepet, oturum, güvenlik, ödeme ve çerez tercihlerinin saklanması için gereklidir.</li>
      <li><strong>İşlevsel çerezler:</strong> Dil, görünüm ve isteğe bağlı kullanım tercihlerinin hatırlanmasını sağlar. Varsayılan olarak kapalıdır.</li>
      <li><strong>Analitik/performans çerezleri:</strong> Google Analytics aracılığıyla ziyaret ve sayfa kullanım istatistiklerinin ölçülmesine yardımcı olur. Varsayılan olarak kapalıdır.</li>
      <li><strong>Reklam/pazarlama çerezleri:</strong> Meta Pixel aracılığıyla reklam kampanyalarının ve dönüşümlerin ölçülmesi için kullanılabilir. Varsayılan olarak kapalıdır.</li>
    </ul>

    <h2>Kullanılan teknolojiler</h2>
    <div class="overflow-x-auto">
      <table>
        <thead>
          <tr>
            <th>Teknoloji</th>
            <th>Sağlayıcı</th>
            <th>Amaç</th>
            <th>Süre</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>site oturum çerezleri</td>
            <td>Puremat Print</td>
            <td>Sitenin ve kullanıcı oturumunun çalışması</td>
            <td>Oturum veya hesap tercihi süresince</td>
          </tr>
          <tr>
            <td>site sepet çerezleri</td>
            <td>Puremat Print</td>
            <td>Sepet içeriğini ve alışveriş oturumunu sürdürmek</td>
            <td>Oturum veya en fazla 2 gün</td>
          </tr>
          <tr>
            <td>Çerez tercih kaydı</td>
            <td>Puremat Print</td>
            <td>Kullanıcının çerez tercihlerini saklamak</td>
            <td>En fazla 12 ay</td>
          </tr>
          <tr>
            <td>_ga ve _ga ölçüm çerezleri</td>
            <td>Google Analytics</td>
            <td>Site kullanımını istatistiksel olarak ölçmek</td>
            <td>En fazla 2 yıl</td>
          </tr>
          <tr>
            <td>_fbp ve _fbc</td>
            <td>Meta Pixel</td>
            <td>Reklam ve dönüşüm performansını ölçmek</td>
            <td>En fazla 90 gün</td>
          </tr>
        </tbody>
      </table>
    </div>
    <p>iyzico’nun kendi ödeme alanındaki çerezler iyzico’nun çerez ve gizlilik metinlerine tabidir.</p>

    <h2>Tercihlerin yönetimi</h2>
    <p>İşlevsel, analitik ve pazarlama çerezleri ilk ziyarette kapalıdır.</p>
    <p>“Tümünü Kabul Et”, “Tümünü Reddet” ve “Tercihleri Yönet” seçenekleri eşdeğer görünürlükte sunulur. Kullanıcı kategori bazında seçim yapabilir ve site alt bilgisindeki “Çerez Tercihleri” bağlantısından tercihini değiştirebilir.</p>
    <p>Google Analytics ve Meta Pixel kodları, etiket yöneticisi kullanılsa dahi ilgili izin verilmeden yüklenmez ve veri göndermez.</p>

    <h2>Hedefli reklam</h2>
    <p>Meta Pixel için pazarlama izni verilmesi hâlinde görüntülenen ürünler, sepete ekleme, ödeme başlangıcı ve satın alma gibi olaylar reklam ölçümü amacıyla kullanılabilir.</p>
    <p>Kullanıcı bu tercihi çerez panelinden reddedebilir veya geri çekebilir. Çocuklara kişisel veriye dayalı profilleme yöntemiyle hedefli reklam yapılmaz.</p>

    <h2>Yurt dışı aktarım</h2>
    <p>Google Analytics ve Meta Pixel, yurt dışındaki sistemlere veri aktarımı doğurabilir. Bu teknolojiler yalnızca açık rıza ve KVKK’nın 9. maddesine uygun aktarım mekanizması sağlandığında çalıştırılır.</p>

    <h2>Çerez tercih banner’ı</h2>
    <p><strong>Başlık:</strong> Çerez tercihlerinizi yönetin</p>
    <p>Sitenin güvenli ve doğru çalışması için zorunlu çerezler kullanıyoruz. Google Analytics ile site ölçümünü ve Meta Pixel ile reklam teknolojilerini yalnızca izniniz ve gerekli yasal aktarım koşulları sağlandığında çalıştırırız.</p>
    <p>Ayrıntıları Çerez Politikası’nda görebilir ve tercihinizi dilediğiniz zaman değiştirebilirsiniz.</p>
    <p><strong>Düğmeler:</strong> Tümünü Reddet · Tercihleri Yönet · Tümünü Kabul Et</p>

    <h3>Tercih paneli</h3>
    <ul>
      <li><strong>Kesinlikle gerekli – Her zaman açık:</strong> Sepet, güvenlik, oturum, ödeme ve çerez tercihlerinin saklanması için gereklidir.</li>
      <li><strong>İşlevsel – Varsayılan kapalı:</strong> Dil, görünüm ve isteğe bağlı kullanım tercihlerinin hatırlanmasına izin verir.</li>
      <li><strong>Analitik/performans – Varsayılan kapalı:</strong> Google Analytics ile site kullanımının ölçülmesine izin verir.</li>
      <li><strong>Reklam/pazarlama – Varsayılan kapalı:</strong> Meta Pixel ile kampanya performansının ve dönüşümlerin ölçülmesine izin verir.</li>
    </ul>
    <p><strong>Panel düğmeleri:</strong> Seçimlerimi Kaydet · Tümünü Reddet · Tümünü Kabul Et</p>

    <p><strong>Yürürlük tarihi:</strong> 4 Ağustos 2026<br><strong>Sürüm:</strong> 4.0</p>

    <p>Diğer yasal metinler için <a href="{{ route('agreements') }}">Sözleşmeler</a> sayfasını ziyaret edebilirsiniz.</p>
  </x-static-page>
@endsection
