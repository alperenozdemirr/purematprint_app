/** blog.html + blog-detail.html — yazı listesi ve detay içeriği */
const BLOG_POSTS = {
  'dijital-baskida-renk-yonetimi': {
    title: 'Dijital Baskıda Renk Yönetimi: CMYK ve Pantone Farkı',
    date: '12 Mayıs 2026',
    category: 'Dijital Baskı',
    image: 'assets/foto3.jpeg',
    excerpt:
      'Ekranda gördüğünüz renk ile baskıdaki ton arasındaki farkı anlamak, marka tutarlılığı için kritik.',
    body: `
      <p>Dijital baskı sürecinde en sık karşılaşılan sorunlardan biri, tasarım dosyasındaki renklerin baskı çıktısında farklı görünmesidir. Bunun temel nedeni RGB (ekran) ile CMYK (baskı) renk uzaylarının birbirinden tamamen farklı çalışmasıdır.</p>
      <h2>CMYK nedir?</h2>
      <p>CMYK; Cyan, Magenta, Yellow ve Key (siyah) harflerinden oluşur. Tüm renkli baskı makineleri bu dört mürekkep tonunu karıştırarak görüntü oluşturur. Parlak neon veya derin lacivert gibi bazı RGB tonları CMYK ile birebir üretilemez.</p>
      <h2>Pantone ne zaman gerekir?</h2>
      <p>Kurumsal kimlik projelerinde logo rengi için Pantone (spot) renk kullanılır. Bu, markanızın her baskıda aynı tonda kalmasını sağlar. Kartvizit, antetli kağıt ve ambalaj gibi kurumsal materyallerde Pantone tercih edilmesini öneriyoruz.</p>
      <h2>Pratik öneriler</h2>
      <ul>
        <li>Tasarımı CMYK modunda hazırlayın veya dönüştürün.</li>
        <li>Kritik işlerde mutlaka dijital prova isteyin.</li>
        <li>Mat ve parlak yüzeylerde renk algısı değişir — malzeme seçimini erken yapın.</li>
      </ul>
    `,
  },
  'magaza-tabelasi-secimi': {
    title: 'Mağaza Tabelası Seçerken Dikkat Edilecek 6 Nokta',
    date: '3 Mayıs 2026',
    category: 'Tabela & Afiş',
    image: 'assets/foto4.jpeg',
    excerpt:
      'Işıklandırma, malzeme ve konumlandırma — vitrininizi öne çıkaran tabela kararları.',
    body: `
      <p>Mağaza tabelası, markanızın sokaktaki ilk temsilcisidir. Doğru malzeme ve ışıklandırma seçimi, yıllarca sorunsuz kullanım sağlar.</p>
      <h2>1. Konum ve görüş açısı</h2>
      <p>Tabelanın cadde veya avm koridorundan ne kadar uzaktan okunabildiğini ölçün. Geniş fontlar ve yüksek kontrast uzaktan fark edilirliği artırır.</p>
      <h2>2. Malzeme seçimi</h2>
      <p>Açık hava için kompozit, paslanmaz veya alüminyum; kapalı alan için forex ve lightbox paneller uygun seçeneklerdir.</p>
      <h2>3. LED aydınlatma</h2>
      <p>Gece görünürlüğü için LED modüller hem enerji tasarrufu sağlar hem de eşit aydınlatma sunar.</p>
      <h2>4. Belediye izinleri</h2>
      <p>Büyük cephe tabelalarında ilgili belediyeden ruhsat gerekebilir — montaj öncesi kontrol edin.</p>
    `,
  },
  'kartvizit-tasarim-ipuclari': {
    title: 'Kartvizit Tasarımında 5 Altın Kural',
    date: '28 Nisan 2026',
    category: 'Kurumsal Kimlik',
    image: 'assets/foto5.jpeg',
    excerpt:
      'Minimal düzen, doğru kağıt ve okunabilir tipografi ile profesyonel ilk izlenim.',
    body: `
      <p>Kartvizit hâlâ B2B ilişkilerde en hızlı iletişim aracıdır. İşte öne çıkan tasarımların ortak özellikleri:</p>
      <ol>
        <li><strong>Boşluk bırakın.</strong> Bilgi kalabalığı profesyonellik algısını düşürür.</li>
        <li><strong>Tek odak noktası.</strong> Logo veya isim — ikisi birden öne çıkmamalı.</li>
        <li><strong>Kağıt hissi.</strong> 350–400 gr mat veya soft-touch kağıt premium algı yaratır.</li>
        <li><strong>Okunabilir font.</strong> 8 pt altına inmeyin; telefon numarası net okunmalı.</li>
        <li><strong>Marka rengi.</strong> Pantone veya CMYK değerlerini dosyaya not edin.</li>
      </ol>
    `,
  },
  'ambalaj-baskisi-surdurulebilirlik': {
    title: 'Ambalaj Baskısında Sürdürülebilir Malzeme Seçenekleri',
    date: '15 Nisan 2026',
    category: 'Ambalaj',
    image: 'assets/foto2.jpeg',
    excerpt:
      'Geri dönüştürülebilir karton, su bazlı mürekkep ve minimal baskı — çevre dostu ambalaj rehberi.',
    body: `
      <p>Tüketiciler ambalaj tercihlerinde sürdürülebilirliği giderek daha fazla önemsiyor. Markanız için hem estetik hem çevre dostu çözümler mümkün.</p>
      <h2>Geri dönüştürülebilir karton</h2>
      <p>FSC sertifikalı kraft ve beyaz kartonlar, gıda dışı ürün ambalajlarında yaygın kullanılır. Baskı sonrası geri dönüşüm oranı yüksektir.</p>
      <h2>Su bazlı mürekkep</h2>
      <p>UV ve solvent bazlı mürekkeplere kıyasla daha düşük VOC emisyonu sunar. Özellikle iç mekân ürün kutularında tercih edilir.</p>
      <h2>Minimal baskı yaklaşımı</h2>
      <p>Daha az renk = daha az mürekkep tüketimi. Tek renk veya iki renk baskı hem maliyeti düşürür hem sürdürülebilirliği artırır.</p>
    `,
  },
  'roll-up-banner-rehberi': {
    title: 'Roll-Up Banner: Etkinlik ve Fuar Kullanım Rehberi',
    date: '2 Nisan 2026',
    category: 'Tabela & Afiş',
    image: 'assets/WhatsApp Image 2026-06-27 at 00.28.43.jpeg',
    excerpt:
      'Taşınabilir, hızlı kurulum ve yüksek görünürlük — roll-up seçim ve bakım ipuçları.',
    body: `
      <p>Roll-up bannerlar fuar, seminer ve mağaza içi kampanyalarda en pratik display çözümlerinden biridir.</p>
      <h2>Boyut seçimi</h2>
      <p>85×200 cm standart fuar ölçüsüdür. Dar stand alanlarında 60×160 cm modeller tercih edilebilir.</p>
      <h2>Baskı kalitesi</h2>
      <p>150 dpi yeterli görünürlük sağlar; yakından okunacak metinler için 300 dpi önerilir.</p>
      <h2>Bakım</h2>
      <p>Mekanizmayı tozdan koruyun, baskıyı rulo halinde sıkı sarmayın. Taşıma çantası kullanım ömrünü uzatır.</p>
    `,
  },
  'kurumsal-kimlik-seti': {
    title: 'Kurumsal Kimlik Seti Nelerden Oluşur?',
    date: '20 Mart 2026',
    category: 'Kurumsal Kimlik',
    image: 'assets/foto1.jpeg',
    excerpt:
      'Logo kullanım kılavuzundan kartvizite — tutarlı marka görünümü için temel set bileşenleri.',
    body: `
      <p>Kurumsal kimlik seti, markanızın tüm temas noktalarında aynı dili konuşmasını sağlar. Temel bileşenler şunlardır:</p>
      <ul>
        <li><strong>Logo ve varyasyonlar</strong> — yatay, dikey, ikon versiyonları</li>
        <li><strong>Renk paleti</strong> — CMYK, RGB ve Pantone değerleri</li>
        <li><strong>Tipografi</strong> — başlık ve gövde fontları</li>
        <li><strong>Kartvizit ve antetli kağıt</strong> — baskıya hazır şablonlar</li>
        <li><strong>Zarf ve dosya</strong> — kurumsal yazışma materyalleri</li>
        <li><strong>Dijital şablonlar</strong> — e-posta imzası, sunum slaytları</li>
      </ul>
      <p>PureMatPrint olarak kurumsal kimlik setinizi tek çatı altında tasarlayıp üretiyoruz.</p>
    `,
  },
};

function initBlogDetail() {
  const root = document.getElementById('blog-article');
  if (!root) return;

  const params = new URLSearchParams(window.location.search);
  const slug = params.get('slug');
  const post = slug ? BLOG_POSTS[slug] : null;

  if (!post) {
    root.innerHTML = `
      <div class="p-8 border-[3px] border-ink bg-surface shadow-brutal-sm text-center">
        <h1 class="font-heading text-page-title font-semibold mb-3">Yazı bulunamadı</h1>
        <p class="text-muted mb-6">Aradığınız blog yazısı mevcut değil veya kaldırılmış olabilir.</p>
        <a href="blog.html" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover">Bloga Dön</a>
      </div>
    `;
    document.title = 'Yazı Bulunamadı — PureMatPrint';
    return;
  }

  document.title = `${post.title} — PureMatPrint`;
  const breadcrumbCurrent = document.getElementById('blog-breadcrumb-current');
  if (breadcrumbCurrent) breadcrumbCurrent.textContent = post.title;

  root.innerHTML = `
    <article>
      <header class="mb-8">
        <div class="flex flex-wrap items-center gap-3 mb-4">
          <span class="px-2.5 py-1 bg-badge text-badge-fg font-body text-[11px] font-semibold tracking-[0.03em] border border-action/15">${post.category}</span>
          <time class="font-body text-xs font-semibold text-muted uppercase tracking-[0.06em]" datetime="">${post.date}</time>
        </div>
        <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] normal-case mb-4">${post.title}</h1>
        <p class="text-[15px] text-muted leading-[1.65] max-w-[65ch]">${post.excerpt}</p>
      </header>
      <div class="border-[3px] border-ink shadow-brutal-sm overflow-hidden mb-10 aspect-[16/9] min-[768px]:aspect-[21/9]">
        <img src="${post.image}" alt="" class="w-full h-full object-cover">
      </div>
      <div class="blog-prose max-w-[68ch] font-body text-[15px] leading-[1.75] text-ink [&_h2]:font-heading [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:mt-9 [&_h2]:mb-3 [&_h2]:normal-case [&_p]:mb-4 [&_p]:text-muted [&_ul]:my-4 [&_ul]:pl-5 [&_ul]:list-disc [&_ul]:text-muted [&_ol]:my-4 [&_ol]:pl-5 [&_ol]:list-decimal [&_ol]:text-muted [&_li]:mb-2 [&_strong]:text-ink">
        ${post.body}
      </div>
    </article>
  `;

  const related = document.getElementById('blog-related');
  if (!related) return;

  const others = Object.entries(BLOG_POSTS)
    .filter(([key]) => key !== slug)
    .slice(0, 3);

  related.innerHTML = others
    .map(
      ([key, item]) => `
      <article class="group border-[3px] border-ink bg-surface shadow-brutal-sm overflow-hidden transition-shadow hover:shadow-brutal">
        <a href="blog-detail.html?slug=${key}" class="block">
          <div class="aspect-[16/10] overflow-hidden border-b-[3px] border-ink">
            <img src="${item.image}" alt="" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
          </div>
          <div class="p-5">
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] text-accent mb-2">${item.category}</p>
            <h3 class="font-heading text-card-title font-semibold leading-snug text-ink group-hover:text-accent transition-colors">${item.title}</h3>
          </div>
        </a>
      </article>
    `,
    )
    .join('');
}

document.addEventListener('DOMContentLoaded', initBlogDetail);
