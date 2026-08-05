@extends('user.layout')
@section('title', 'Sözleşmeler')

@section('content')
  <section class="pt-10 md:pt-14 pb-16 md:pb-20">
    <div class="container mx-auto px-4 max-w-6xl">
      <header class="mb-8 md:mb-10">
        <p class="font-body text-[11px] font-bold uppercase tracking-[0.14em] text-accent mb-3">Yasal metinler</p>
        <h1 class="font-heading text-3xl md:text-4xl font-semibold text-ink tracking-tight">Sözleşmeler</h1>
        <p class="mt-3 max-w-3xl text-[15px] leading-relaxed text-muted">
          Puremat Print sipariş, teslimat, cayma, gizlilik ve ilgili yasal metinler. İlgili bölüme gitmek için aşağıdaki bağlantıları kullanabilirsiniz.
        </p>
      </header>

      <div class="lg:grid lg:grid-cols-[240px_minmax(0,1fr)] lg:gap-8 lg:items-start">
        <nav class="mb-8 lg:mb-0 lg:sticky lg:top-24 border-[3px] border-ink bg-surface shadow-brutal-sm p-4 text-[13px]" aria-label="Sözleşme bölümleri">
          <p class="font-heading text-sm font-semibold text-ink mb-3">İçindekiler</p>
          <ul class="space-y-2 text-muted [&_a]:underline [&_a]:underline-offset-2 hover:[&_a]:text-accent">
            <li><a href="#sartlar-ve-kosullar">Şartlar ve Koşullar</a></li>
            <li><a href="#islem-rehberi">İşlem Rehberi</a></li>
            <li><a href="#teslimat-iptal-iade-cayma">Teslimat, İptal, İade ve Cayma</a></li>
            <li><a href="#on-bilgilendirme-formu">Ön Bilgilendirme Formu</a></li>
            <li><a href="#mesafeli-satis-sozlesmesi">Mesafeli Satış Sözleşmesi</a></li>
            <li><a href="#cayma-formu">Cayma Formu</a></li>
            <li><a href="#ticari-musteri-kosullari">Ticari Müşteri Koşulları</a></li>
            <li><a href="#kvkk-aydinlatma">KVKK Aydınlatma Metni</a></li>
            <li><a href="#gizlilik-politikasi">Gizlilik Politikası</a></li>
            <li><a href="#odeme-ekrani-onay">Ödeme Ekranı Onay Metinleri</a></li>
            <li><a href="#ticari-elektronik-ileti">Ticari Elektronik İleti Onayı</a></li>
            <li><a href="{{ route('cookies') }}">Çerez Politikası</a></li>
          </ul>
        </nav>

        <div class="border-[3px] border-ink bg-surface shadow-brutal-sm p-6 md:p-8 text-[15px] leading-[1.75] text-muted space-y-14 [&_h2]:font-heading [&_h2]:text-xl [&_h2]:md:text-2xl [&_h2]:font-semibold [&_h2]:text-ink [&_h2]:mb-4 [&_h2]:scroll-mt-28 [&_h3]:font-heading [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:text-ink [&_h3]:mt-6 [&_h3]:mb-2 [&_p+p]:mt-4 [&_ul]:mt-4 [&_ul]:space-y-2 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:mt-4 [&_ol]:space-y-3 [&_ol]:list-decimal [&_ol]:pl-5 [&_li]:leading-[1.7] [&_a]:text-accent [&_a]:font-semibold [&_a]:underline [&_a]:underline-offset-[3px] hover:[&_a]:text-accent-dark [&_strong]:text-ink [&_hr]:my-8 [&_hr]:border-ink/15">

          {{-- 3. Şartlar ve Koşullar --}}
          <article id="sartlar-ve-kosullar">
            <h2>Şartlar ve Koşullar</h2>
            <p><strong>Yürürlük tarihi:</strong> 4 Ağustos 2026<br><strong>Sürüm:</strong> 4.0</p>

            <h3>1. Taraflar ve kapsam</h3>
            <p>Bu Şartlar ve Koşullar; purematprint.com internet sitesinin kullanımını ve GENÇ PRINT REKLAM ANONİM ŞİRKETİ tarafından Puremat Print markasıyla sunulan ürün ve hizmetlere ilişkin siparişleri düzenler.</p>
            <p>Site sepeti ve ödeme ekranı, WhatsApp, canlı destek, e-posta, teklif formu veya iyzico ödeme linki üzerinden verilen siparişler bu koşullar kapsamındadır.</p>
            <p>Ticari veya mesleki olmayan amaçlarla hareket eden gerçek veya tüzel kişiler “Tüketici”; ticari ya da mesleki amaçla hareket eden kişiler ve kurumlar “Ticari Müşteri” olarak anılır. Tüketici işlemlerindeki emredici haklar saklıdır.</p>
            <p>Bu metin yalnızca Türkiye’ye teslim edilen siparişler için geçerlidir. Avrupa Birliği ve diğer yurt dışı teslimat seçenekleri aktif değildir.</p>

            <h3>2. Site kullanımı</h3>
            <p>Kullanıcı; siteyi hukuka uygun kullanacağını, sitenin güvenliğini veya işleyişini bozacak eylemlerde bulunmayacağını, yetkisiz erişim girişiminde bulunmayacağını ve üçüncü kişilerin haklarını ihlal eden içerikler iletmeyeceğini kabul eder.</p>
            <p>Bakım, güncelleme veya güvenlik gereksinimleri nedeniyle siteye erişim geçici olarak sınırlandırılabilir. Bu durum daha önce kurulmuş sipariş sözleşmelerinden doğan hak ve yükümlülükleri ortadan kaldırmaz.</p>

            <h3>3. Ürün bilgileri ve görseller</h3>
            <p>Ürünün adı, malzemesi, ölçüsü, rengi, adedi, baskı veya uygulama yöntemi, kullanım alanı ve kişiselleştirme seçenekleri ürün sayfasında, sipariş özetinde veya yazılı teklifte belirtilir.</p>
            <p>Ekran, ortam ışığı, malzeme yüzeyi, baskı yöntemi ve üretim partisi nedeniyle dijital görüntü ile fiziksel ürün arasında makul ton veya doku farklılıkları oluşabilir. Bu açıklama, ürünün kararlaştırılan temel niteliklere veya onaylı üretim dosyasına aykırı olmasını uygun hâle getirmez.</p>
            <p>Temsili mekân görselleri ve dijital uygulamalar ürünün olası kullanımını göstermek amacıyla kullanılabilir. Bağlayıcı ürün özellikleri ürün sayfasında, sipariş özetinde ve onaylı üretim dosyasında belirtilen bilgilerdir.</p>

            <h3>4. Fiyatlar ve indirimler</h3>
            <p>Tüketici satışlarında fiyatlar, Satıcı tarafından tahsil edilen KDV ve diğer vergiler dâhil gösterilir. Kargo, tasarım, montaj, özel paketleme veya başka bir ek bedel bulunması hâlinde bu bedel sipariş verilmeden önce ayrıca gösterilir.</p>
            <p>İndirimli mal satışlarında indirim başlangıcından önceki son on gün içinde uygulanan en düşük fiyat, indirimden önceki fiyat olarak esas alınır. Kampanya, kupon ve indirim koşulları ilan edilen süre ve kapsam için geçerlidir.</p>
            <p>Açık yazım veya sistem hatası bulunan fiyatlarda müşteri sipariş kabulünden önce bilgilendirilir. Müşteri düzeltilmiş fiyatı kabul etmezse sipariş kurulmaz; tahsilat yapılmışsa bedel kullanılan ödeme aracına uygun şekilde iade edilir.</p>

            <h3>5. Siparişin kurulması</h3>
            <p>Site üzerinden siparişte müşteri; ürün ve kişiselleştirme seçeneklerini belirler, teslimat ve fatura bilgilerini girer, ödeme yöntemini seçer, sipariş özetini kontrol eder ve Ön Bilgilendirme Formu ile Mesafeli Satış Sözleşmesine erişir.</p>
            <p>Sözleşme, tüketicinin ödeme yükümlülüğü doğurduğu açıkça belirtilen sipariş düğmesiyle siparişi onaylaması ve Satıcının sipariş kabul bildiriminin tüketiciye kalıcı veri saklayıcısıyla ulaşmasıyla kurulur.</p>
            <p>Yalnızca sipariş talebinin alındığını bildiren otomatik mesaj, açıkça sipariş kabulü olarak belirtilmedikçe kabul anlamına gelmez.</p>
            <p>WhatsApp, canlı destek, e-posta veya iyzico ödeme linki üzerinden tamamlanan tüketici siparişlerinde de siparişe özel ürün bilgileri, toplam fiyat, teslimat koşulları, Ön Bilgilendirme Formu ve Mesafeli Satış Sözleşmesi ödeme öncesinde tüketiciye gönderilir ve teyidi kaydedilir.</p>

            <h3>6. Tasarım ve kişiye özel üretim</h3>
            <p>Logo, isim, özel metin, müşteriye özgü ölçü, renk, kesim, baskı veya tasarıma göre hazırlanan ürünlerde üretim, müşterinin yazılı veya sonradan doğrulanabilir dijital onayından sonra başlar.</p>
            <p>Müşteri; metin, yazım, tarih, telefon numarası, QR kod, ölçü, renk, logo ve görsel unsurları onaydan önce kontrol eder.</p>
            <p>Onaydan sonra istenen değişiklikler üretim aşamasına göre mümkün olmayabilir; ek bedel veya teslim süresi doğurabilir. Satıcının ağır kusuru, açık teknik uygunsuzluk ve ayıplı ürün hâlleri saklıdır.</p>
            <p>Tüketicinin istekleri veya kişisel ihtiyaçları doğrultusunda hazırlanan mallarda kanuni cayma hakkı kullanılamaz. Bir ürünün yalnızca standart seçeneklerden seçilmesi veya “özel üretim” olarak adlandırılması tek başına cayma hakkı istisnası oluşturmaz.</p>

            <h3>7. Müşteri dosyaları ve fikrî haklar</h3>
            <p>Müşteri; ilettiği logo, marka, fotoğraf, tasarım, yazı tipi, metin ve diğer içerikleri kullanma ve çoğaltma yetkisine sahip olduğunu beyan eder.</p>
            <p>Hak ihlali veya hukuka aykırılık şüphesi bulunan sipariş reddedilebilir ve izin belgesi istenebilir.</p>
            <p>Müşteri dosyaları yalnızca teklif, tasarım, üretim, kalite kontrolü, satış sonrası destek ve hukuki yükümlülüklerin yerine getirilmesi amacıyla kullanılır. Müşteri içeriği ayrıca izin alınmadıkça reklam veya portföy amacıyla yayımlanmaz.</p>

            <h3>8. Ödeme</h3>
            <p>Kullanılabilir ödeme yöntemleri sipariş ekranında gösterilir. Kredi/banka kartı ve ödeme linki işlemleri, iyzi Ödeme ve Elektronik Para Hizmetleri A.Ş. tarafından sunulan iyzico altyapısıyla yürütülür.</p>
            <p>Tam kart numarası ve kart güvenlik kodu Puremat Print tarafından saklanmaz. Puremat Print yalnızca işlem referansı, ödeme durumu ve maskelenmiş kart bilgilerini alabilir.</p>
            <p>Taksit, 3D Secure, provizyon ve toplam ödeme bilgileri sipariş onayından önce gösterilir.</p>
            <p>Havale/EFT seçeneğinde sipariş numarası ödeme açıklamasına yazılmalıdır. Bedel Şirket hesabına geçmeden üretim ve hazırlık süresi başlamaz.</p>

            <h3>9. Teslimat</h3>
            <p>Türkiye içi gönderiler Aras Kargo ile yapılır. Ürüne özel hazırlık ve tahminî teslim süresi ürün sayfasında, sipariş özetinde veya yazılı teklifte gösterilir.</p>
            <p>Standart malların teslimi mevzuattaki azami otuz günlük süreyi aşamaz. Tüketicinin istekleri veya kişisel ihtiyaçları doğrultusunda hazırlanan mallara ilişkin mevzuat istisnası saklı olmakla birlikte sipariş sırasında taahhüt edilen teslim süresi Satıcıyı bağlar.</p>
            <p>Siparişin ifasının imkânsızlaştığı öğrenildiğinde tüketici en geç üç gün içinde yazılı olarak veya kalıcı veri saklayıcısıyla bilgilendirilir. Tahsil edilen bedeller, bildirim tarihinden itibaren en geç on dört gün içinde iade edilir. Malın stokta bulunmaması tek başına ifa imkânsızlığı sayılmaz.</p>
            <p>Satıcının belirlediği taşıyıcı dışında tüketicinin başka bir taşıyıcı seçmesi hariç, ürün tüketiciye veya tüketicinin belirlediği üçüncü kişiye teslim edilinceye kadar kayıp ve hasar riski Satıcıya aittir.</p>

            <h3>10. Cayma ve iade</h3>
            <p>Kanuni istisna bulunmayan mesafeli sözleşmelerde tüketicinin on dört günlük cayma hakkı vardır.</p>
            <p>Cayma bildiriminin ardından tüketici, Satıcı ürünü kendisinin alacağını bildirmedikçe ürünü on dört gün içinde Aras Kargo’ya teslim eder. Aras Kargo kullanılarak yapılan cayma iadelerinde iade kargo masrafı Satıcıya aittir.</p>
            <p>Tüketicinin bulunduğu yerde Aras Kargo şubesi yoksa Satıcı, tüketiciden ek ücret almadan ürünün alınmasını sağlar.</p>
            <p>Mal teslim edildikten sonraki caymada geri ödeme süresi ürünün Aras Kargo’ya teslim edildiği tarihte başlar. Ürün farklı bir taşıyıcıyla gönderilirse süre ürünün Satıcıya ulaştığı tarihte başlar.</p>
            <p>Mal teslim edilmeden önceki caymada ve hizmet sözleşmelerinde süre, cayma bildiriminin Satıcıya ulaştığı tarihte başlar.</p>
            <p>Geri ödemeler on dört gün içinde, satın alırken kullanılan ödeme aracına uygun biçimde, tüketiciye masraf yüklenmeden ve tek seferde yapılır.</p>
            <p>Kişiye özel ürün istisnası, ayıplı veya sözleşmeye aykırı üründen doğan hakları ortadan kaldırmaz.</p>

            <h3>11. Ayıplı mal ve hizmet</h3>
            <p>Ürün ayıplı veya siparişe aykırıysa tüketici kanuni şartlar çerçevesinde sözleşmeden dönme, bedel indirimi, ücretsiz onarım veya imkân varsa ayıpsız misliyle değişim haklarından birini kullanabilir.</p>
            <p>Ayıplı hizmetlerde hizmetin yeniden görülmesi, ortaya çıkan eserin ücretsiz onarımı, bedel indirimi veya sözleşmeden dönme hakları saklıdır.</p>

            <h3>12. Kişisel veriler ve ticari ileti</h3>
            <p>Kişisel veriler KVKK Aydınlatma Metni, Gizlilik Politikası ve Çerez Politikası kapsamında işlenir.</p>
            <p>Google Analytics ve Meta Pixel gibi zorunlu olmayan teknolojiler açık rıza alınmadan ve gerekli yurt dışı aktarım koşulları sağlanmadan çalıştırılmaz.</p>
            <p>Reklam ve kampanya iletileri için verilen onay sipariş vermenin şartı değildir.</p>

            <h3>13. Uyuşmazlık</h3>
            <p>Başvurular <a href="mailto:hello@purematprint.com">hello@purematprint.com</a>, <a href="mailto:gencprintreklam@hs01.kep.tr">gencprintreklam@hs01.kep.tr</a> veya Şirketin kayıtlı adresine yöneltilebilir.</p>
            <p>Tüketiciler, yürürlükteki görev ve parasal sınırlar kapsamında tüketici hakem heyetine veya 6502 sayılı Kanun’un 73/A maddesi uyarınca dava açmadan önce arabuluculuğa başvurulması şartıyla tüketici mahkemesine başvurabilir.</p>
            <p>Tüketicinin yerleşim yerindeki ve tüketici işleminin yapıldığı yerdeki emredici yetki kuralları saklıdır.</p>
          </article>

          <hr>

          {{-- 4. İşlem Rehberi --}}
          <article id="islem-rehberi">
            <h2>İşlem Rehberi</h2>

            <h3>Site üzerinden sipariş</h3>
            <ol>
              <li>Ürün seçilir; ölçü, renk, adet, malzeme ve kişiselleştirme seçenekleri belirlenir.</li>
              <li>Ürün sepete eklenir.</li>
              <li>Teslimat ve fatura bilgileri girilir.</li>
              <li>Aras Kargo teslimatı ve uygulanacak kargo bedeli gösterilir.</li>
              <li>iyzico kartlı ödeme, havale/EFT veya sunulan diğer ödeme yöntemi seçilir.</li>
              <li>Ürünlerin temel nitelikleri, indirimler, KDV, kargo ve ek hizmetler dâhil toplam bedel kontrol edilir.</li>
              <li>Ön Bilgilendirme Formu ve Mesafeli Satış Sözleşmesi ödeme öncesinde görüntülenir.</li>
              <li>Kişiye özel üretim veya tasarım hizmetinin erken başlatılması söz konusuysa ilgili onay ayrıca alınır.</li>
              <li>Ödeme yükümlülüğü doğurduğu açıkça belirtilen sipariş düğmesiyle sipariş onaylanır.</li>
              <li>Siparişin alındığı ve kabul edildiği bilgisi e-posta veya başka bir kalıcı veri saklayıcısıyla gönderilir.</li>
            </ol>

            <h3>WhatsApp ve canlı destek siparişleri</h3>
            <p>Müşteri ürün türünü, ölçüyü, adedi, teslimat yerini ve üretim dosyalarını WhatsApp veya canlı destek üzerinden iletebilir.</p>
            <p>Tüketici siparişlerinde ödeme linki gönderilmeden önce ürünün temel nitelikleri, kişiselleştirme bilgileri, KDV ve kargo dâhil toplam fiyatı, tahminî hazırlık ve teslim süresi, Ön Bilgilendirme Formu, Mesafeli Satış Sözleşmesi ve uygulanacak cayma koşulları tüketiciye kalıcı veri saklayıcısıyla iletilir.</p>
            <p>Tüketicinin teyidi kaydedildikten sonra iyzico ödeme linki gönderilebilir.</p>

            <h3>Veri giriş hataları</h3>
            <p>Sipariş onayından önce ürün, adet, teslimat adresi, fatura ve iletişim bilgileri değiştirilebilir.</p>
            <p>Sipariş onayından sonra fark edilen hata, üretim başlamadan önce müşteri hizmetlerine bildirilmelidir. Kişiye özel üretimde onay sonrası değişiklik mümkün olmayabilir veya ek bedel doğurabilir.</p>

            <h3>Kayıtların saklanması</h3>
            <p>Sipariş özeti, Ön Bilgilendirme Formu, Mesafeli Satış Sözleşmesi, tasarım ve kişiselleştirme onayları, teslimat ve cayma/iade kayıtları en az üç yıl saklanır.</p>
            <p>Müşteri hesabı kullanılıyorsa belgeler hesap alanında gösterilebilir. Hesap kullanılmıyorsa belgeler e-posta veya başka bir kalıcı veri saklayıcısıyla gönderilir.</p>
          </article>

          <hr>

          {{-- 5. Teslimat, İptal, İade ve Cayma --}}
          <article id="teslimat-iptal-iade-cayma">
            <h2>Teslimat, İptal, İade ve Cayma Politikası</h2>
            <p><strong>Yürürlük tarihi:</strong> 4 Ağustos 2026<br><strong>Sürüm:</strong> 4.0</p>

            <h3>Teslimat</h3>
            <p>Bu politika yalnızca Türkiye içindeki siparişler için geçerlidir. Anlaşmalı teslimat ve iade taşıyıcısı Aras Kargo’dur.</p>
            <p>Ürüne özel hazırlık ve tahminî teslim süresi ürün sayfasında, sipariş özetinde veya yazılı teklifte gösterilir. Kişiye özel üretimde süre, ödeme ve üretim dosyası onayı tamamlandıktan sonra başlar.</p>
            <p>Standart malların teslimi mevzuattaki azami otuz günlük süreyi aşamaz. Tüketicinin istekleri veya kişisel ihtiyaçları doğrultusunda hazırlanan ürünlere ilişkin mevzuat istisnası saklıdır.</p>

            <h3>Teslim alma ve taşıma hasarı</h3>
            <p>Pakette ezilme, yırtılma, kırılma veya ıslanma gibi belirgin bir hasar bulunması hâlinde taşıyıcı görevlisiyle tutanak düzenlenmesi ve fotoğraf alınması incelemeyi hızlandırır.</p>
            <p>Tutanak tutulmamış olması tüketicinin ayıplı mala ilişkin kanuni haklarını ortadan kaldırmaz.</p>

            <h3>Siparişin imkânsızlaşması</h3>
            <p>Siparişin yerine getirilmesinin imkânsızlaştığı öğrenildiğinde tüketici en geç üç gün içinde yazılı olarak veya kalıcı veri saklayıcısıyla bilgilendirilir.</p>
            <p>Varsa teslimat bedeli dâhil tahsil edilen ödemeler, bildirim tarihinden itibaren en geç on dört gün içinde iade edilir. Malın stokta bulunmaması tek başına ifa imkânsızlığı sayılmaz.</p>

            <h3>Cayma hakkı</h3>
            <p>Kanuni istisna bulunmayan mesafeli sözleşmelerde tüketici on dört gün içinde gerekçe göstermeden ve cezai şart ödemeden cayabilir.</p>
            <p>Cayma süresi hizmet sözleşmelerinde sözleşmenin kurulduğu gün; mal satışlarında tüketicinin veya tüketicinin belirlediği taşıyıcı dışındaki üçüncü kişinin malı teslim aldığı gün başlar.</p>
            <p>Tüketici, mal teslim edilmeden önce de cayma hakkını kullanabilir.</p>

            <h3>Cayma bildirimi</h3>
            <p>Cayma bildirimi aşağıdaki kanallardan biriyle yapılabilir:</p>
            <ul>
              <li><a href="mailto:hello@purematprint.com">hello@purematprint.com</a></li>
              <li><a href="mailto:gencprintmuhasebe@hotmail.com">gencprintmuhasebe@hotmail.com</a></li>
              <li><a href="mailto:gencprintreklam@hs01.kep.tr">gencprintreklam@hs01.kep.tr</a></li>
              <li>Sitedeki <a href="#cayma-formu">cayma formu</a></li>
              <li>Maltepe Mah. Litros Yolu Sk. D Blok No: 2-4D/Z16, Zeytinburnu/İstanbul, Türkiye</li>
            </ul>
            <p>Sitedeki cayma formuyla gönderilen bildirimlerin alındığı bilgisi tüketiciye derhal iletilir.</p>

            <h3>Kişiye özel ürünler</h3>
            <p>Tüketicinin istekleri veya kişisel ihtiyaçları doğrultusunda hazırlanan; müşteriye özgü isim, logo, metin, ölçü, renk, kesim, baskı veya tasarım içeren mallarda cayma hakkı kullanılamaz.</p>
            <p>Katalogda standart olarak sunulan ve tüketiciye özgü üretim gerektirmeyen ürünler yalnızca “özel üretim” olarak adlandırıldıkları için otomatik olarak istisna sayılmaz.</p>
            <p>Kişiye özel ürün istisnası, ayıplı veya siparişe aykırı maldan doğan hakları ortadan kaldırmaz.</p>

            <h3>Tasarım hizmeti</h3>
            <p>Tasarım veya başka bir hizmet, tüketicinin açık talebi ve onayıyla on dört günlük cayma süresi sona ermeden başlatılırsa tüketici, hizmetin ifasına başlanmasıyla bu hizmet bakımından cayma hakkını kaybeder.</p>
            <p>Ayıplı hizmetten ve sözleşmeye aykırı ifadan doğan kanuni haklar saklıdır.</p>

            <h3>Ürünün geri gönderilmesi</h3>
            <p>Satıcı ürünü kendisinin alacağını bildirmedikçe tüketici, cayma bildiriminden itibaren on dört gün içinde ürünü Aras Kargo’ya teslim eder.</p>
            <ul>
              <li><strong>İade alıcısı:</strong> GENÇ PRINT REKLAM ANONİM ŞİRKETİ</li>
              <li><strong>İade adresi:</strong> Maltepe Mah. Litros Yolu Sk. D Blok No: 2-4D/Z16, Zeytinburnu/İstanbul, Türkiye</li>
              <li><strong>İade taşıyıcısı:</strong> Aras Kargo</li>
              <li><strong>İade işlemleri e-postası:</strong> <a href="mailto:gencprintmuhasebe@hotmail.com">gencprintmuhasebe@hotmail.com</a></li>
            </ul>
            <p>Aras Kargo ile yapılan cayma iadelerinin kargo masrafı Satıcı tarafından karşılanır.</p>
            <p>Tüketicinin bulunduğu yerde Aras Kargo şubesi bulunmaması hâlinde Satıcı, tüketiciden ek ücret almadan ürünün bulunduğu yerden alınmasını sağlar.</p>

            <h3>Geri ödeme</h3>
            <p>Mal teslim edildikten sonra cayma hakkı kullanılmışsa Satıcı, caymaya konu ürünün Aras Kargo’ya teslim edildiği tarihten itibaren on dört gün içinde tahsil edilen ödemeleri iade eder.</p>
            <p>Ürün Aras Kargo dışında bir taşıyıcıyla gönderilmişse on dört günlük süre ürünün Satıcıya ulaştığı tarihte başlar.</p>
            <p>Mal teslim edilmeden önceki caymada veya hizmet sözleşmesinde süre, cayma bildiriminin Satıcıya ulaştığı tarihte başlar.</p>
            <p>Geri ödeme; varsa tüketiciden tahsil edilen teslimat bedeli dâhil olmak üzere, satın alırken kullanılan ödeme aracına uygun biçimde, tüketiciye ek masraf yüklenmeden ve tek seferde yapılır.</p>

            <h3>Ayıplı mal ve hizmet</h3>
            <p>Ürünün siparişe, onaylı üretim dosyasına veya objektif olarak sahip olması gereken özelliklere aykırı olması hâlinde tüketici kanuni şartlar çerçevesinde:</p>
            <ul>
              <li>Sözleşmeden dönme ve bedel iadesi,</li>
              <li>Ayıp oranında bedel indirimi,</li>
              <li>Ücretsiz onarım,</li>
              <li>İmkân varsa ayıpsız misliyle değişim</li>
            </ul>
            <p>haklarından birini kullanabilir.</p>
            <p>Ayıplı hizmetlerde hizmetin yeniden görülmesi, ortaya çıkan eserin ücretsiz onarımı, bedel indirimi veya sözleşmeden dönme hakları saklıdır.</p>
            <p>Ayıplı, yanlış veya taşıma sırasında zarar görmüş ürünlere ilişkin iade ve inceleme masrafları Satıcıya aittir.</p>
          </article>

          <hr>

          {{-- 6. Ön Bilgilendirme Formu --}}
          <article id="on-bilgilendirme-formu">
            <h2>Ön Bilgilendirme Formu</h2>
            <p>Bu formun ayrılmaz parçası olan sipariş özetinde; tüketicinin adı ve iletişim bilgileri, teslimat ve fatura adresi, sipariş numarası, ürün veya hizmetin temel nitelikleri, kişiselleştirme bilgileri, adet, birim fiyat, indirim, ek hizmetler, kargo bedeli, KDV dâhil toplam tutar, ödeme yöntemi ve tahminî teslim süresi gösterilmektedir.</p>

            <h3>Satıcı</h3>
            <ul>
              <li><strong>Ticari unvan:</strong> GENÇ PRINT REKLAM ANONİM ŞİRKETİ</li>
              <li><strong>Marka/site:</strong> Puremat Print – purematprint.com</li>
              <li><strong>Adres:</strong> Maltepe Mah. Litros Yolu Sk. D Blok No: 2-4D/Z16, Zeytinburnu/İstanbul, Türkiye</li>
              <li><strong>MERSİS:</strong> 0393115305900001</li>
              <li><strong>Ticaret Sicil No:</strong> 341553-5</li>
              <li><strong>Vergi dairesi / VKN:</strong> Davutpaşa Vergi Dairesi Müdürlüğü / 3931153059</li>
              <li><strong>KEP:</strong> <a href="mailto:gencprintreklam@hs01.kep.tr">gencprintreklam@hs01.kep.tr</a></li>
              <li><strong>E-posta:</strong> <a href="mailto:hello@purematprint.com">hello@purematprint.com</a></li>
              <li><strong>Telefon:</strong> <a href="tel:+905364624480">+90 536 462 44 80</a></li>
            </ul>

            <h3>Ödeme</h3>
            <p>Siparişte seçilen ödeme yöntemi ve tüketicinin ödeyeceği toplam tutar ödeme öncesinde sipariş özetinde gösterilir.</p>
            <p>Kart ve ödeme linki işlemleri iyzi Ödeme ve Elektronik Para Hizmetleri A.Ş. tarafından sunulan iyzico altyapısıyla yürütülür. Tam kart numarası ve kart güvenlik kodu Puremat Print tarafından saklanmaz.</p>
            <p>Havale/EFT seçilmişse ödeme Şirket hesabına geçmeden hazırlık ve üretim süresi başlamaz.</p>

            <h3>Teslimat</h3>
            <p>Türkiye içi gönderiler Aras Kargo ile yapılır. Ürüne özel hazırlık ve tahminî teslim süresi sipariş özetinde gösterilir.</p>
            <p>Standart malların teslimi mevzuattaki azami otuz günlük süreyi aşamaz. Tüketicinin istekleri veya kişisel ihtiyaçları doğrultusunda hazırlanan mallara ilişkin mevzuat istisnası saklıdır.</p>

            <h3>Cayma hakkı</h3>
            <p>Kanuni istisna bulunmayan mesafeli sözleşmelerde tüketici, malı teslim aldığı tarihten; hizmet sözleşmesinde sözleşmenin kurulduğu tarihten itibaren on dört gün içinde gerekçe göstermeden ve cezai şart ödemeden cayabilir.</p>
            <p>Tüketici mal teslim edilmeden önce de cayma hakkını kullanabilir.</p>
            <p>Cayma bildirimi:</p>
            <ul>
              <li><a href="mailto:hello@purematprint.com">hello@purematprint.com</a>,</li>
              <li><a href="mailto:gencprintmuhasebe@hotmail.com">gencprintmuhasebe@hotmail.com</a>,</li>
              <li><a href="mailto:gencprintreklam@hs01.kep.tr">gencprintreklam@hs01.kep.tr</a>,</li>
              <li>Sitedeki cayma formu,</li>
              <li>Satıcının kayıtlı adresi</li>
            </ul>
            <p>üzerinden yapılabilir.</p>
            <p>Tüketici, Satıcı ürünü kendisinin alacağını bildirmedikçe cayma bildiriminden itibaren on dört gün içinde ürünü Aras Kargo’ya teslim eder.</p>
            <p>Aras Kargo ile yapılan cayma iadelerinde iade kargo masrafı Satıcı tarafından karşılanır. Tüketicinin bulunduğu yerde Aras Kargo şubesi yoksa Satıcı ürünü tüketiciden ek ücret almadan aldırır.</p>

            <h3>Kişiye özel ürünler</h3>
            <p>Tüketicinin istekleri veya kişisel ihtiyaçları doğrultusunda hazırlanan; müşteriye özgü logo, isim, metin, ölçü, renk, kesim, baskı veya tasarım içeren mallarda cayma hakkı kullanılamaz.</p>
            <p>Ayıplı veya siparişe aykırı üründen doğan haklar saklıdır.</p>

            <h3>Tasarım hizmetinin başlatılması</h3>
            <p>Tasarım hizmetinin tüketicinin açık talebi ve onayıyla cayma süresi dolmadan başlatılması hâlinde tüketici, hizmetin ifasına başlanmasıyla bu hizmet bakımından cayma hakkını kaybeder.</p>
            <p>Ayıplı hizmetten doğan haklar saklıdır.</p>

            <h3>Geri ödeme</h3>
            <p>Mal teslim edildikten sonraki caymada geri ödeme süresi ürünün Aras Kargo’ya teslim edildiği tarihte başlar. Ürün farklı bir taşıyıcıyla gönderilirse süre ürünün Satıcıya ulaştığı tarihte başlar.</p>
            <p>Mal teslim edilmeden önceki caymada ve hizmet sözleşmelerinde süre, cayma bildiriminin Satıcıya ulaştığı tarihte başlar.</p>
            <p>Geri ödeme on dört gün içinde, satın alırken kullanılan ödeme aracına uygun şekilde, tüketiciye masraf yüklenmeden ve tek seferde yapılır.</p>

            <h3>Uyuşmazlık</h3>
            <p>Tüketici, yürürlükteki görev ve parasal sınırlar kapsamında tüketici hakem heyetine veya 6502 sayılı Kanun’un 73/A maddesi uyarınca dava açmadan önce arabuluculuğa başvurulması şartıyla tüketici mahkemesine başvurabilir.</p>
            <p>Tüketici, ödeme yükümlülüğü doğuran siparişini vermeden önce bu Ön Bilgilendirme Formuna ve siparişe özgü bilgilere eriştiğini teyit eder.</p>
          </article>

          <hr>

          {{-- 7. Mesafeli Satış Sözleşmesi --}}
          <article id="mesafeli-satis-sozlesmesi">
            <h2>Mesafeli Satış Sözleşmesi</h2>

            <h3>Madde 1 – Taraflar</h3>
            <p><strong>Satıcı:</strong> GENÇ PRINT REKLAM ANONİM ŞİRKETİ</p>
            <ul>
              <li><strong>Marka/site:</strong> Puremat Print – purematprint.com</li>
              <li><strong>Adres:</strong> Maltepe Mah. Litros Yolu Sk. D Blok No: 2-4D/Z16, Zeytinburnu/İstanbul, Türkiye</li>
              <li><strong>MERSİS:</strong> 0393115305900001</li>
              <li><strong>Vergi dairesi / VKN:</strong> Davutpaşa Vergi Dairesi Müdürlüğü / 3931153059</li>
              <li><strong>KEP:</strong> <a href="mailto:gencprintreklam@hs01.kep.tr">gencprintreklam@hs01.kep.tr</a></li>
              <li><strong>E-posta:</strong> <a href="mailto:hello@purematprint.com">hello@purematprint.com</a></li>
              <li><strong>Telefon:</strong> <a href="tel:+905364624480">+90 536 462 44 80</a></li>
            </ul>
            <p><strong>Alıcı/Tüketici:</strong> Sipariş sırasında beyan edilen ad, soyad, telefon, e-posta, teslimat ve fatura adresi bilgileri bu Sözleşmenin ayrılmaz parçasıdır.</p>

            <h3>Madde 2 – Konu</h3>
            <p>Bu Sözleşme, Alıcının Satıcıya ait çevrimiçi mağaza veya WhatsApp/canlı destek gibi uzaktan iletişim kanalları üzerinden sipariş verdiği mal ve/veya hizmetlerin satışı, tasarımı, kişiselleştirilmesi, üretimi, ödenmesi ve teslimiyle tarafların hak ve yükümlülüklerini düzenler.</p>
            <p>6502 sayılı Tüketicinin Korunması Hakkında Kanun, Mesafeli Sözleşmeler Yönetmeliği ve ilgili mevzuat uygulanır.</p>

            <h3>Madde 3 – Sipariş bilgileri</h3>
            <p>Sipariş özetinde gösterilen sipariş numarası, ürün veya hizmetin temel nitelikleri, malzeme, ölçü, renk, adet, kişiselleştirme bilgileri, onaylı üretim dosyası, birim fiyat, indirim, ek hizmet, kargo, KDV dâhil genel toplam, ödeme yöntemi, teslimat adresi ve tahminî teslim süresi bu Sözleşmenin ayrılmaz parçasıdır.</p>

            <h3>Madde 4 – Sözleşmenin kurulması</h3>
            <p>Site satışında Sözleşme, Alıcının ödeme yükümlülüğünü açıkça gösteren sipariş düğmesine basması ve Satıcının sipariş kabul bildiriminin Alıcıya kalıcı veri saklayıcısıyla ulaşmasıyla kurulur.</p>
            <p>WhatsApp, canlı destek, e-posta veya ödeme linki satışında Sözleşme; sipariş özeti, Ön Bilgilendirme Formu ve bu Sözleşmenin ödeme öncesinde Alıcıya iletilmesi, Alıcının teyidi ve Satıcının siparişi kabul etmesiyle kurulur.</p>
            <p>Sipariş kabul edilmezse Alıcı bilgilendirilir ve tahsil edilen tutar kullanılan ödeme aracına uygun biçimde iade edilir.</p>

            <h3>Madde 5 – Ödeme</h3>
            <p>Sipariş özetinde gösterilen toplam bedel, KDV ve ayrıca gösterilen kargo ile ek hizmet bedellerini içerir.</p>
            <p>Kart ve ödeme linki işlemleri iyzico üzerinden yürütülür. Tam kart numarası ve kart güvenlik kodu Satıcı tarafından saklanmaz.</p>
            <p>Havale/EFT seçilmişse ödeme Satıcı hesabına geçmeden hazırlık süresi başlamaz.</p>

            <h3>Madde 6 – Tasarım ve kişiselleştirme</h3>
            <p>Kişiye özel üretimde üretim, Alıcının yazılı veya sonradan doğrulanabilir dijital onayından sonra başlar.</p>
            <p>Alıcı; metin, yazım, tarih, telefon, QR kod, ölçü, logo, renk ve görsel unsurları kontrol eder. Onaydan sonra istenen değişiklikler ek bedel ve süre doğurabilir.</p>
            <p>Satıcının ağır kusuru, açık teknik uygunsuzluk ve ayıplı ifa hâlleri saklıdır.</p>

            <h3>Madde 7 – Teslimat</h3>
            <p>Türkiye içi gönderiler Aras Kargo ile yapılır.</p>
            <p>Standart mallar taahhüt edilen süre içinde ve her durumda mevzuattaki azami otuz günlük süre aşılmadan teslim edilir. Tüketicinin istekleri veya kişisel ihtiyaçları doğrultusunda hazırlanan mallara ilişkin mevzuat istisnası saklıdır.</p>
            <p>Siparişin ifasının imkânsızlaştığı öğrenildiğinde Alıcı en geç üç gün içinde yazılı olarak veya kalıcı veri saklayıcısıyla bilgilendirilir. Tahsil edilen ödemeler bildirim tarihinden itibaren en geç on dört gün içinde iade edilir.</p>

            <h3>Madde 8 – Cayma hakkı</h3>
            <p>Kanuni istisna bulunmayan siparişlerde Alıcı, malı teslim aldığı tarihten; hizmette Sözleşmenin kurulduğu tarihten itibaren on dört gün içinde gerekçe göstermeden ve cezai şart ödemeden cayabilir.</p>
            <p>Cayma bildirimi <a href="mailto:hello@purematprint.com">hello@purematprint.com</a>, <a href="mailto:gencprintmuhasebe@hotmail.com">gencprintmuhasebe@hotmail.com</a>, <a href="mailto:gencprintreklam@hs01.kep.tr">gencprintreklam@hs01.kep.tr</a>, sitedeki cayma formu veya Satıcının kayıtlı adresine yöneltilebilir.</p>

            <h3>Madde 9 – Cayma hakkı istisnası</h3>
            <p>Alıcının istekleri veya kişisel ihtiyaçları doğrultusunda hazırlanan; müşteriye özgü isim, logo, metin, ölçü, renk, kesim, baskı veya tasarım içeren mallarda cayma hakkı kullanılamaz.</p>
            <p>Tasarım veya başka bir hizmetin Alıcının açık talebi ve onayıyla cayma süresi sona ermeden başlatılması hâlinde Alıcı, hizmetin ifasına başlanmasıyla bu hizmet bakımından cayma hakkını kaybeder.</p>
            <p>Ayıplı mal, ayıplı hizmet ve sözleşmeye aykırı ifadan doğan haklar saklıdır.</p>

            <h3>Madde 10 – İade</h3>
            <p>Satıcı ürünü kendisinin alacağını bildirmedikçe Alıcı, cayma bildiriminden itibaren on dört gün içinde ürünü Aras Kargo’ya teslim eder.</p>
            <p><strong>İade adresi:</strong> Maltepe Mah. Litros Yolu Sk. D Blok No: 2-4D/Z16, Zeytinburnu/İstanbul, Türkiye</p>
            <p>Aras Kargo ile yapılan cayma iadelerinde iade kargo masrafı Satıcıya aittir. Alıcının bulunduğu yerde Aras Kargo şubesi yoksa Satıcı ürünü ek ücret almadan aldırır.</p>

            <h3>Madde 11 – Geri ödeme</h3>
            <p>Mal teslim edildikten sonraki caymada Satıcı, ürünün Aras Kargo’ya teslim edildiği tarihten itibaren on dört gün içinde geri ödeme yapar.</p>
            <p>Ürün başka bir taşıyıcıyla gönderilirse süre ürünün Satıcıya ulaştığı tarihte başlar.</p>
            <p>Mal teslim edilmeden önceki caymada ve hizmet sözleşmelerinde süre, cayma bildiriminin Satıcıya ulaştığı tarihte başlar.</p>
            <p>Geri ödeme, varsa tahsil edilen teslimat bedeli dâhil olmak üzere satın alırken kullanılan ödeme aracına uygun şekilde, Alıcıya masraf yüklenmeden ve tek seferde yapılır.</p>

            <h3>Madde 12 – Ayıplı mal ve hizmet</h3>
            <p>Alıcının ayıplı mal bakımından sözleşmeden dönme, bedel indirimi, ücretsiz onarım veya imkân varsa ayıpsız misliyle değişim hakları saklıdır.</p>
            <p>Ayıplı hizmetlerde hizmetin yeniden görülmesi, eserin ücretsiz onarımı, bedel indirimi veya sözleşmeden dönme hakları kullanılabilir.</p>
            <p>Seçimlik hakkın kullanılmasından doğan masraflar Satıcıya aittir.</p>

            <h3>Madde 13 – Kişisel veriler</h3>
            <p>Kişisel veriler KVKK Aydınlatma Metninde açıklanan amaç, yöntem ve hukuki sebeplerle işlenir.</p>
            <p>Pazarlama onayı Sözleşmenin kurulmasının şartı değildir ve ayrı alınır. Zorunlu olmayan çerezler açık rıza verilmeden çalıştırılmaz.</p>

            <h3>Madde 14 – Uyuşmazlık</h3>
            <p>Alıcı, yürürlükteki görev ve parasal sınırlar kapsamında tüketici hakem heyetine veya 6502 sayılı Kanun’un 73/A maddesi uyarınca dava açmadan önce arabuluculuğa başvurulması şartıyla tüketici mahkemesine başvurabilir.</p>
            <p>Tüketicinin yerleşim yeri ve işlemin yapıldığı yere ilişkin emredici yetki kuralları saklıdır.</p>

            <h3>Madde 15 – Kayıt ve onay</h3>
            <p>Sipariş özeti, Ön Bilgilendirme Formu, bu Sözleşme, kişiselleştirme ve erken hizmet ifası onayları Alıcıya kalıcı veri saklayıcısıyla gönderilir ve en az üç yıl saklanır.</p>
            <p>Alıcı, ödeme yükümlülüğü doğuran siparişini vermeden önce bu Sözleşmeye eriştiğini ve siparişe özgü bilgilerin kendisine gösterildiğini teyit eder.</p>
          </article>

          <hr>

          {{-- 8. Cayma Formu --}}
          <article id="cayma-formu">
            <h2>Cayma Formu</h2>
            <p>Bu form yalnızca cayma hakkının bulunduğu sözleşmelerde kullanılabilir. Formun kullanılması zorunlu değildir; cayma kararını açıkça bildiren başka bir yazılı beyan da kullanılabilir.</p>
            <ul>
              <li><strong>Satıcı:</strong> GENÇ PRINT REKLAM ANONİM ŞİRKETİ</li>
              <li><strong>Adres:</strong> Maltepe Mah. Litros Yolu Sk. D Blok No: 2-4D/Z16, Zeytinburnu/İstanbul, Türkiye</li>
              <li><strong>E-posta:</strong> <a href="mailto:hello@purematprint.com">hello@purematprint.com</a> / <a href="mailto:gencprintmuhasebe@hotmail.com">gencprintmuhasebe@hotmail.com</a></li>
              <li><strong>KEP:</strong> <a href="mailto:gencprintreklam@hs01.kep.tr">gencprintreklam@hs01.kep.tr</a></li>
            </ul>
            <ul class="!list-none !pl-0 space-y-3 mt-6">
              <li><strong>Tüketicinin adı ve soyadı:</strong><br>............................................................</li>
              <li><strong>Tüketicinin adresi:</strong><br>............................................................</li>
              <li><strong>Telefon / e-posta:</strong><br>............................................................</li>
              <li><strong>Sipariş numarası:</strong><br>............................................................</li>
              <li><strong>Sipariş tarihi:</strong><br>............................................................</li>
              <li><strong>Teslim tarihi:</strong><br>............................................................</li>
              <li><strong>Caymaya konu ürün veya hizmet:</strong><br>............................................................</li>
              <li><strong>Adet:</strong><br>............................................................</li>
              <li><strong>Bildirim tarihi:</strong><br>............................................................</li>
            </ul>
            <p class="mt-6"><strong>Cayma beyanı:</strong><br>Yukarıda bilgileri bulunan malın satışına veya hizmetin sunulmasına ilişkin sözleşmeden cayma hakkımı kullandığımı bildiririm.</p>
            <p><strong>İmza:</strong><br>Yalnızca form kâğıt üzerinde gönderiliyorsa imzalanır.</p>
          </article>

          <hr>

          {{-- 9. Ticari Müşteri --}}
          <article id="ticari-musteri-kosullari">
            <h2>Ticari Müşteri Üretim ve Satış Koşulları</h2>
            <p>Bu koşullar yalnızca ticari veya mesleki amaçla hareket eden müşterilere uygulanır. Tüketici işlemlerindeki emredici haklar bu metinle sınırlandırılamaz.</p>

            <h3>Teklif ve sipariş</h3>
            <p>Teklif; ürün, malzeme, ölçü, baskı veya uygulama, adet, fiyat, vergi, ödeme, teslimat ve varsa montaj kapsamıyla birlikte değerlendirilir.</p>
            <p>Teklifte belirtilen geçerlilik süresi uygulanır. Sipariş, müşterinin yazılı veya sonradan doğrulanabilir dijital kabulü ve Puremat Print’in sipariş kabulüyle kurulur.</p>

            <h3>Fiyat ve ödeme</h3>
            <p>KDV’nin fiyata dâhil olup olmadığı teklifte belirtilir. Tasarım, numune, montaj, özel paketleme ve kargo yalnızca teklif kapsamındaysa fiyata dâhildir.</p>
            <p>Vadesi gelen ödemenin yapılmaması hâlinde üretim veya teslimat durdurulabilir. Gecikmeden doğan kanuni haklar saklıdır.</p>

            <h3>Teknik bilgiler</h3>
            <p>Müşteri, keşif veya ölçülendirme hizmeti ayrıca sipariş edilmedikçe ilettiği ölçü, montaj yüzeyi, kullanım alanı ve teknik bilgilerin doğruluğundan sorumludur.</p>
            <p>Puremat Print, makul mesleki dikkatle fark edilebilen açık teknik çelişkileri üretimden önce bildirir.</p>
            <p>Ürüne özgü kesim, büküm, baskı, renk ve adet toleransları ürün sayfasında veya teklifte ayrıca belirtilir.</p>

            <h3>Tasarım onayı</h3>
            <p>Üretim müşteri onayından sonra başlar. Müşteri; metin, tarih, QR kod, iletişim bilgisi, renk kodu, ölçü, logo ve görsel unsurları kontrol eder.</p>
            <p>Onay sonrası değişiklikler ek ücret ve süre doğurabilir. Puremat Print’in ağır kusuru, açık teknik uygunsuzluk ve ayıplı ifa hâlleri saklıdır.</p>

            <h3>Renk ve numune</h3>
            <p>Ekran, baskı teknolojisi, yüzey, ışık ve üretim partisi nedeniyle renk ve doku farklılıkları oluşabilir.</p>
            <p>Kritik renk eşleşmesinde müşteri yazılı renk kodu vermeli ve gerekli görülürse ücretli fiziksel numune onaylamalıdır. Onaylı numune varsa değerlendirme numuneye göre yapılır.</p>

            <h3>İptal ve değişiklik</h3>
            <p>Ticari müşteri siparişindeki iptal veya değişiklik Puremat Print’in yazılı kabulüne tabidir.</p>
            <p>Tasarım, malzeme tedariki, kalıp, kesim, baskı, işçilik, dış hizmet ve lojistik için doğmuş siparişe özgü makul maliyetler müşteriye yansıtılabilir.</p>

            <h3>Teslimat ve inceleme</h3>
            <p>Teslimat yöntemi ve süresi teklifte gösterilir. Ticari müşteri ürünü teslim alırken miktar ve görünür hasar yönünden kontrol eder.</p>
            <p>Ayıp inceleme ve bildirimleri Türk Ticaret Kanunu’ndaki süre ve usullere uygun şekilde yapılır.</p>

            <h3>Montaj</h3>
            <p>Montaj ayrıca sipariş edilmedikçe ürün teslimi montajı içermez.</p>
            <p>Montaj işinde elektrik, taşıyıcı yüzey, erişim, izin, çalışma alanı güvenliği ve müşteri tarafından sağlanacak altyapı teklif üzerinde belirlenir. Eksik veya yanlış saha bilgisi ek maliyet ve süre doğurabilir.</p>

            <h3>Gizlilik ve portföy</h3>
            <p>Taraflar teklif, fiyat, üretim dosyası ve ticari sır niteliğindeki bilgileri amaç dışında kullanmaz.</p>
            <p>Müşteriye ait iş, marka veya mekân görseli ayrıca yazılı izin alınmadıkça Puremat Print portföyünde veya reklamlarında yayımlanmaz.</p>

            <h3>Uyuşmazlık</h3>
            <p>Ticari müşteri işlemlerine Türk hukuku uygulanır. Uyuşmazlıklarda kanunen yetkili İstanbul mahkemeleri ve icra daireleri yetkilidir.</p>
          </article>

          <hr>

          {{-- 10. KVKK --}}
          <article id="kvkk-aydinlatma">
            <h2>KVKK Aydınlatma Metni</h2>
            <ul>
              <li><strong>Veri sorumlusu:</strong> GENÇ PRINT REKLAM ANONİM ŞİRKETİ</li>
              <li><strong>Marka:</strong> Puremat Print</li>
              <li><strong>Adres:</strong> Maltepe Mah. Litros Yolu Sk. D Blok No: 2-4D/Z16, Zeytinburnu/İstanbul, Türkiye</li>
              <li><strong>KEP:</strong> <a href="mailto:gencprintreklam@hs01.kep.tr">gencprintreklam@hs01.kep.tr</a></li>
              <li><strong>E-posta:</strong> <a href="mailto:hello@purematprint.com">hello@purematprint.com</a></li>
              <li><strong>Telefon:</strong> <a href="tel:+905364624480">+90 536 462 44 80</a></li>
            </ul>

            <h3>İşlenen kişisel veriler</h3>
            <p>Faaliyetin niteliğine göre aşağıdaki kişisel veriler işlenebilir:</p>
            <ul>
              <li>Kimlik bilgileri,</li>
              <li>Telefon, e-posta, teslimat ve fatura adresleri,</li>
              <li>Teklif, sepet, sipariş, sözleşme, tasarım onayı, cayma, iade, şikâyet ve destek kayıtları,</li>
              <li>Logo, fotoğraf, metin, renk, ölçü, tasarım dosyası ve üretim onayı,</li>
              <li>Ödeme yöntemi, işlem referansı, ödeme durumu, maskelenmiş kart bilgileri, fatura ve muhasebe kayıtları,</li>
              <li>Kargo, takip, teslimat, iade ve hasar kayıtları,</li>
              <li>IP adresi, tarih-saat, oturum, cihaz, tarayıcı, hata ve güvenlik kayıtları,</li>
              <li>Ticari ileti onay/ret kayıtları ile çerez ve reklam tercihleri.</li>
            </ul>
            <p>Tam kart numarası ve kart güvenlik kodu Puremat Print tarafından saklanmaz; iyzico ödeme ortamında işlenir.</p>

            <h3>İşleme amaçları ve hukuki sebepler</h3>
            <p>Kişisel veriler:</p>
            <ul>
              <li>Teklif hazırlamak,</li>
              <li>Siparişi kurmak ve yerine getirmek,</li>
              <li>Kişiye özel tasarım ve üretim yapmak,</li>
              <li>Ödemeyi doğrulamak,</li>
              <li>Fatura ve muhasebe işlemlerini yürütmek,</li>
              <li>Teslimat, iade, cayma ve müşteri desteği sağlamak,</li>
              <li>Dolandırıcılık ve kötüye kullanımı önlemek,</li>
              <li>Hukuki talepleri ve uyuşmazlıkları yönetmek,</li>
              <li>Kanuni kayıt yükümlülüklerini yerine getirmek,</li>
              <li>Açık rıza verilmesi hâlinde site ölçümü ve reklam performansı çalışmaları yapmak,</li>
              <li>Ticari elektronik ileti onayı verilmesi hâlinde kampanya ve duyuru göndermek</li>
            </ul>
            <p>amaçlarıyla işlenir.</p>
            <p>İşleme faaliyetleri; sözleşmenin kurulması veya ifası, hukuki yükümlülük, bir hakkın tesisi, kullanılması veya korunması, ilgili kişinin temel haklarına zarar vermeyen meşru menfaat ve gerekli hâllerde açık rıza hukuki sebeplerine dayanır.</p>

            <h3>Toplama yöntemleri</h3>
            <p>Veriler; site formları, sepet ve ödeme ekranı, kullanıcı hesabı, dosya yükleme, e-posta, telefon, WhatsApp, canlı destek, çerezler ve benzeri teknolojiler ile iyzico, bankalar ve Aras Kargo gibi hizmet sağlayıcılardan elektronik veya fiziki yollarla elde edilebilir.</p>
            <p>Sipariş için zorunlu bilgilerin verilmemesi teklif, ödeme, üretim veya teslimat sürecini engelleyebilir. Pazarlama izni ve zorunlu olmayan çerez tercihleri isteğe bağlıdır.</p>

            <h3>Verilerin paylaşılması</h3>
            <p>Veriler, amaçla sınırlı olmak üzere:</p>
            <ul>
              <li>iyzi Ödeme ve Elektronik Para Hizmetleri A.Ş. ve bankalar,</li>
              <li>Aras Kargo,</li>
              <li>Site barındırma ve bilgi teknolojisi sağlayıcıları,</li>
              <li>Canlı destek hizmeti sağlayıcıları,</li>
              <li>Muhasebe, mali müşavirlik ve hukuk danışmanları,</li>
              <li>Siparişin üretimi için zorunlu tedarik ve hizmet sağlayıcıları,</li>
              <li>Kanunen yetkili kamu kurumları, mahkemeler ve icra mercileri</li>
            </ul>
            <p>ile paylaşılabilir.</p>

            <h3>Yurt dışına aktarım</h3>
            <p>Google Analytics, Meta Pixel, WhatsApp/Instagram ve Microsoft tabanlı e-posta hizmetleri kullanıldığında bazı kişisel veriler yurt dışında işlenebilir veya yurt dışından erişilebilir hâle gelebilir.</p>
            <p>Düzenli ve sürekli nitelikteki yurt dışı aktarımlar yalnızca KVKK’nın 9. maddesinde öngörülen yeterlilik kararı veya standart sözleşme gibi uygun güvence yöntemlerinden uygulanabilir olanı sağlandığında gerçekleştirilir.</p>
            <p>Geçerli aktarım mekanizması bulunmayan zorunlu olmayan hizmetler çalıştırılmaz. Google Analytics ve Meta Pixel ayrıca ilgili çerez kategorisi için açık rıza verilmeden etkinleştirilmez.</p>

            <h3>Saklama süreleri</h3>
            <ul>
              <li>Sipariş, ön bilgilendirme, sözleşme, teslimat ve cayma/iade kayıtları en az üç yıl,</li>
              <li>Fatura, muhasebe ve vergi kayıtları ilgili mevzuatta öngörülen süre boyunca,</li>
              <li>Üretim dosyaları ve onaylar siparişin ifası ve hukuki talepler için gerekli süre boyunca,</li>
              <li>Ticari ileti onay ve ret kayıtları ispat ve mevzuat yükümlülüğü devam ettiği sürece,</li>
              <li>Çerezler Çerez Politikasında belirtilen süre boyunca,</li>
              <li>Güvenlik kayıtları hizmet güvenliği ve olay incelemesi için gerekli sınırlı süre boyunca</li>
            </ul>
            <p>saklanır.</p>

            <h3>KVKK kapsamındaki haklar</h3>
            <p>KVKK’nın 11. maddesi uyarınca ilgili kişi:</p>
            <ul>
              <li>Kişisel verilerinin işlenip işlenmediğini öğrenebilir,</li>
              <li>İşlenmişse bilgi talep edebilir,</li>
              <li>İşleme amacını ve amaca uygun kullanılıp kullanılmadığını öğrenebilir,</li>
              <li>Verilerin aktarıldığı üçüncü kişileri bilebilir,</li>
              <li>Eksik veya yanlış verilerin düzeltilmesini isteyebilir,</li>
              <li>Kanuni şartları varsa verilerin silinmesini veya yok edilmesini isteyebilir,</li>
              <li>Bu işlemlerin verilerin aktarıldığı üçüncü kişilere bildirilmesini isteyebilir,</li>
              <li>Münhasıran otomatik sistemlerle analiz sonucu aleyhe bir sonucun ortaya çıkmasına itiraz edebilir,</li>
              <li>Kanuna aykırı işleme nedeniyle zarar doğması hâlinde giderim talep edebilir.</li>
            </ul>

            <h3>Başvuru</h3>
            <p>Başvurular:</p>
            <ul>
              <li>GENÇ PRINT REKLAM ANONİM ŞİRKETİ, Maltepe Mah. Litros Yolu Sk. D Blok No: 2-4D/Z16, Zeytinburnu/İstanbul adresine yazılı olarak,</li>
              <li><a href="mailto:gencprintreklam@hs01.kep.tr">gencprintreklam@hs01.kep.tr</a> KEP adresine,</li>
              <li>Daha önce sistemde kayıtlı e-posta adresi üzerinden <a href="mailto:hello@purematprint.com">hello@purematprint.com</a> adresine,</li>
              <li>KVKK mevzuatında kabul edilen diğer doğrulanabilir yöntemlerle</li>
            </ul>
            <p>gönderilebilir.</p>
            <p>Başvurular talebin niteliğine göre en kısa sürede ve en geç otuz gün içinde sonuçlandırılır.</p>
          </article>

          <hr>

          {{-- 11. Gizlilik --}}
          <article id="gizlilik-politikasi">
            <h2>Gizlilik Politikası</h2>
            <p>Puremat Print yalnızca teklif, sipariş, tasarım, üretim, ödeme, teslimat, müşteri desteği, güvenlik ve kanuni yükümlülükler için gerekli bilgileri işler.</p>
            <p>Kart ve ödeme linki işlemleri iyzico üzerinden yürütülür. Tam kart numarası ve kart güvenlik kodu Puremat Print tarafından saklanmaz.</p>
            <p>Baskı veya tasarım için yüklenen dosyalar yalnızca teklif, üretim, kalite kontrolü, satış sonrası destek ve hukuki taleplerin yönetimi amacıyla kullanılır. Müşteri dosyaları ayrıca izin alınmadıkça reklam veya portföy amacıyla yayımlanmaz.</p>
            <p>Google Analytics ve Meta Pixel varsayılan olarak kapalıdır. Kullanıcı ilgili çerez kategorisine izin vermeden ve gerekli yurt dışı aktarım koşulları sağlanmadan çalıştırılmaz.</p>
            <p>WhatsApp veya canlı destek tercih edilirse yazışmalar sipariş ve destek amacıyla işlenebilir. Kullanıcıların sipariş için gerekli olmayan özel nitelikli kişisel verileri bu kanallardan göndermemesi gerekir.</p>
            <p>Kişisel verileri yetkisiz erişim, kayıp, değişiklik veya açıklamaya karşı korumak amacıyla erişim yetkileri, parola ve çok faktörlü doğrulama, güncelleme, yedekleme, kayıt ve hizmet sağlayıcı kontrolleri gibi uygun teknik ve idari tedbirler uygulanır.</p>
            <p>Sipariş onayı, ödeme, tasarım onayı, güvenlik, kargo, teslimat ve müşteri hizmeti mesajları sözleşmenin yürütülmesi amacıyla gönderilebilir. Reklam ve kampanya iletileri için gereken onay ayrı ve isteğe bağlıdır.</p>
            <p>Sorular ve kişisel veri başvuruları <a href="mailto:hello@purematprint.com">hello@purematprint.com</a> adresine iletilebilir.</p>
            <p><strong>Yürürlük tarihi:</strong> 4 Ağustos 2026<br><strong>Sürüm:</strong> 4.0</p>
          </article>

          <hr>

          {{-- 14. Ödeme Ekranı Onay --}}
          <article id="odeme-ekrani-onay">
            <h2>Ödeme Ekranı Onay Metinleri</h2>

            <h3>Sözleşme onayı — zorunlu</h3>
            <p>[ ] Sipariş özetimi, Ön Bilgilendirme Formunu ve Mesafeli Satış Sözleşmesini okudum. Siparişimin ödeme yükümlülüğü doğurduğunu biliyorum.</p>

            <h3>Kişiye özel ürün onayı — yalnızca kişiye özel siparişlerde zorunlu</h3>
            <p>[ ] Siparişimin tarafımdan iletilen logo, isim, metin, ölçü, renk, kesim, baskı, tasarım veya diğer kişisel talimatlara göre hazırlanacağını; bu nedenle ürün bakımından kanuni cayma hakkı istisnasının uygulanacağını biliyorum. Ayıplı veya siparişe aykırı üründen doğan haklarımın saklı olduğunu kabul ediyorum.</p>

            <h3>Tasarım hizmetinin erken başlatılması — uygulanıyorsa zorunlu</h3>
            <p>[ ] Tasarım hizmetinin on dört günlük cayma süresi sona ermeden başlatılmasını açıkça talep ediyor ve onaylıyorum. Hizmetin ifasına başlanmasıyla bu hizmet bakımından cayma hakkımı kaybedeceğimi; ayıplı hizmetten doğan haklarımın saklı olduğunu biliyorum.</p>
          </article>

          <hr>

          {{-- 15. Ticari Elektronik İleti --}}
          <article id="ticari-elektronik-ileti">
            <h2>Ticari Elektronik İleti Onayı</h2>
            <p>GENÇ PRINT REKLAM ANONİM ŞİRKETİ tarafından Puremat Print ürün, hizmet, kampanya, indirim, etkinlik ve duyurularına ilişkin ticari elektronik iletilerin yalnızca seçtiğim kanallardan gönderilmesine onay veriyorum.</p>
            <p>Bu onayın sipariş vermenin veya hizmet almanın şartı olmadığını; dilediğim zaman hiçbir gerekçe göstermeden ve ücretsiz olarak ret hakkımı kullanabileceğimi biliyorum.</p>
            <ul class="!list-none !pl-0">
              <li>[ ] E-posta</li>
              <li>[ ] SMS ve WhatsApp mesajı</li>
              <li>[ ] Telefon araması</li>
            </ul>
            <p>Onay kutuları önceden işaretli değildir. Kullanıcı yalnızca seçtiği kanala onay verir.</p>
            <p>Ret bildirimi ulaştıktan sonra ilgili kanaldaki reklam ve kampanya iletileri en geç üç iş günü içinde durdurulur.</p>
            <p>Onay; iletilerdeki ret seçeneği, <a href="mailto:hello@purematprint.com">hello@purematprint.com</a>, <a href="tel:+905364624480">+90 536 462 44 80</a> veya İleti Yönetim Sistemi üzerinden geri çekilebilir.</p>
            <p>Sipariş onayı, ödeme, tasarım onayı, güvenlik, kargo, teslimat ve müşteri hizmeti mesajları bu pazarlama onayından bağımsız olarak siparişin yürütülmesi amacıyla gönderilebilir.</p>
          </article>

          <p class="pt-4 border-t border-ink/15 text-[14px]">
            Çerez Politikası ayrı sayfada yer alır: <a href="{{ route('cookies') }}">Çerez Politikası</a>.
          </p>
        </div>
      </div>
    </div>
  </section>
@endsection
