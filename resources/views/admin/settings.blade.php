@extends('admin.layout')
@section('title', 'Sistem Ayarları')
@section('page_title', 'Sistem Ayarları')
@section('breadcrumb', 'Sistem / Ayarlar')

@section('content')
  <div class="mb-6">
    <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Sistem Ayarları</h2>
    <p class="font-body text-[13px] text-muted">Site durumu, kargo ve indirim kurallarını yönetin</p>
  </div>

  <form action="{{ route('admin.settingsUpdate') }}" method="POST" enctype="multipart/form-data" class="grid gap-6">
    @csrf

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Site Durumu</h3>
      </div>
      <div class="grid gap-5 p-5">
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="hidden" name="site_open" value="0">
          <input type="checkbox" name="site_open" value="1" class="h-4 w-4 accent-accent" @checked(old('site_open', $setting->site_open))>
          <span class="font-body text-[14px] text-ink">Site açık (kapalıysa ziyaretçiler bakım sayfasını görür)</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="hidden" name="show_real_homepage_reviews" value="0">
          <input type="checkbox" name="show_real_homepage_reviews" value="1" class="h-4 w-4 accent-accent" @checked(old('show_real_homepage_reviews', $setting->show_real_homepage_reviews))>
          <span class="font-body text-[14px] text-ink">Anasayfada gerçek müşteri yorumlarını göster (kapalıysa demo yorumlar görünür)</span>
        </label>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">İndirim Ayarları</h3>
      </div>
      <div class="grid gap-5 p-5">
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="hidden" name="discount_enabled" value="0">
          <input type="checkbox" id="discount_enabled" name="discount_enabled" value="1" class="h-4 w-4 accent-accent" @checked(old('discount_enabled', $setting->discount_enabled))>
          <span class="font-body text-[14px] text-ink">İndirim aktif</span>
        </label>

        <div id="discount-fields" class="grid gap-5 md:grid-cols-2">
          <div>
            <label for="discount_type" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İndirim Türü</label>
            <select id="discount_type" name="discount_type" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
              <option value="">Seçin</option>
              @foreach ($discountTypes as $type)
                <option value="{{ $type->value }}" @selected(old('discount_type', $setting->discount_type?->value) === $type->value)>{{ $type->label() }}</option>
              @endforeach
            </select>
            @error('discount_type') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="discount_value" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İndirim Değeri</label>
            <input type="number" step="0.01" min="0" id="discount_value" name="discount_value" value="{{ old('discount_value', $setting->discount_value) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                   placeholder="Örn. 10 veya 50">
            <p id="discount_value_hint" class="mt-1.5 font-body text-[12px] text-muted hidden">Yüzdelik indirim en fazla 100 olabilir.</p>
            @error('discount_value') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div class="md:col-span-2">
            <label for="discount_scope" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İndirim Kapsamı</label>
            <select id="discount_scope" name="discount_scope" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
              @foreach ($discountScopes as $scope)
                <option value="{{ $scope->value }}" @selected(old('discount_scope', $setting->discount_scope?->value) === $scope->value)>{{ $scope->label() }}</option>
              @endforeach
            </select>
            @error('discount_scope') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Kargo Ayarları</h3>
      </div>
      <div class="grid gap-5 p-5">
        <div>
          <label for="shipping_mode" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kargo Modu</label>
          <select id="shipping_mode" name="shipping_mode" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
            @foreach ($shippingModes as $mode)
              <option value="{{ $mode->value }}" @selected(old('shipping_mode', $setting->shipping_mode?->value) === $mode->value)>{{ $mode->label() }}</option>
            @endforeach
          </select>
          @error('shipping_mode') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div id="shipping-paid-fields" class="grid gap-5 md:grid-cols-2">
          <div>
            <label for="shipping_fee" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kargo Ücreti (₺)</label>
            <input type="number" step="0.01" min="0" id="shipping_fee" name="shipping_fee" value="{{ old('shipping_fee', $setting->shipping_fee) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
            @error('shipping_fee') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div class="md:col-span-2">
            <label class="flex items-center gap-3 cursor-pointer mb-3">
              <input type="hidden" name="shipping_free_limit_enabled" value="0">
              <input type="checkbox" id="shipping_free_limit_enabled" name="shipping_free_limit_enabled" value="1" class="h-4 w-4 accent-accent" @checked(old('shipping_free_limit_enabled', $setting->shipping_free_limit_enabled))>
              <span class="font-body text-[14px] text-ink">Belirli tutar üzeri ücretsiz kargo uygula</span>
            </label>
            <input type="number" step="0.01" min="0" id="shipping_free_limit" name="shipping_free_limit" value="{{ old('shipping_free_limit', $setting->shipping_free_limit) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                   placeholder="Örn. 1000">
            @error('shipping_free_limit') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
          <div>
            <label for="shipping_duration_text" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kargolama Süresi Metni</label>
            <input type="text" id="shipping_duration_text" name="shipping_duration_text" value="{{ old('shipping_duration_text', $setting->shipping_duration_text) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                   placeholder="Boş bırakılırsa: 3–5 Gün">
            <p class="mt-1.5 font-body text-[12px] text-muted">Ürün detay ve kargo açıklamasında kargoya verilme süresi.</p>
            @error('shipping_duration_text') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="delivery_time_text" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Teslimat Süresi Metni</label>
            <input type="text" id="delivery_time_text" name="delivery_time_text" value="{{ old('delivery_time_text', $setting->delivery_time_text) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                   placeholder="Boş bırakılırsa: 1–5 Gün">
            <p class="mt-1.5 font-body text-[12px] text-muted">Ürün detay sayfasındaki teslimat rozeti metni.</p>
            @error('delivery_time_text') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Logo</h3>
      </div>
      <div class="grid gap-5 p-5">
        @if ($setting->hasCustomLogo())
          <div class="flex items-center gap-4">
            <img src="{{ $setting->logoUrl() }}" alt="Site logosu" class="h-12 w-auto max-w-[200px] object-contain border border-ink/10 rounded-lg bg-cream p-2">
            <p class="font-body text-[13px] text-muted">Mevcut logo yüklü. Yeni dosya seçerseniz eskisi silinir.</p>
          </div>
        @else
          <p class="font-body text-[13px] text-muted">Logo yüklenmemiş — sitede varsayılan logo gösterilir.</p>
        @endif
        <div>
          <label for="logo" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Logo Yükle</label>
          <input type="file" id="logo" name="logo" accept="{{ \App\Support\ImageUploadRules::acceptAttribute() }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('logo') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Anasayfa — Giriş</h3>
        <p class="mt-1 font-body text-[12px] text-muted">Hero alanındaki arka plan görseli, başlık ve açıklama metni. Boş bırakılırsa varsayılan içerik gösterilir.</p>
      </div>
      <div class="grid gap-5 p-5 md:grid-cols-2">
        <div class="md:col-span-2">
          @if ($setting->hasCustomIntroImage())
            <div class="mb-4 flex items-center gap-4">
              <img src="{{ $setting->introImageUrl() }}" alt="Giriş görseli" class="h-24 w-auto max-w-[280px] rounded-lg border border-ink/10 bg-cream object-cover p-1">
              <p class="font-body text-[13px] text-muted">Mevcut görsel yüklü. Yeni dosya seçerseniz eskisi silinir.</p>
            </div>
          @else
            <p class="mb-4 font-body text-[13px] text-muted">Görsel yüklenmemiş — varsayılan hero görseli kullanılır.</p>
          @endif
          <label for="intro_image" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Giriş Görseli</label>
          <input type="file" id="intro_image" name="intro_image" accept="{{ \App\Support\ImageUploadRules::acceptAttribute() }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('intro_image') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="intro_title" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Giriş Başlık</label>
          <textarea id="intro_title" name="intro_title" rows="2"
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                    placeholder="{{ str_replace("\n", ' / ', \App\Models\Setting::DEFAULT_INTRO_TITLE) }}">{{ old('intro_title', $setting->intro_title) }}</textarea>
          <p class="mt-1.5 font-body text-[12px] text-muted">İkinci satır için Enter ile alt satıra geçin (ör. Markanı / yükselt.).</p>
          @error('intro_title') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="intro_description" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Giriş Açıklama</label>
          <textarea id="intro_description" name="intro_description" rows="3"
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                    placeholder="{{ \App\Models\Setting::DEFAULT_INTRO_DESCRIPTION }}">{{ old('intro_description', $setting->intro_description) }}</textarea>
          @error('intro_description') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Anasayfa — Spotlight</h3>
        <p class="mt-1 font-body text-[12px] text-muted">Görsel, alıntı metni ve sağ alt marka yazısı. Boş bırakılırsa varsayılan içerik gösterilir.</p>
      </div>
      <div class="grid gap-5 p-5 md:grid-cols-2">
        <div class="md:col-span-2">
          @if ($setting->hasCustomSpotlightImage())
            <div class="mb-4 flex items-center gap-4">
              <img src="{{ $setting->spotlightImageUrl() }}" alt="Spotlight görseli" class="h-24 w-auto max-w-[280px] rounded-lg border border-ink/10 bg-cream object-cover p-1">
              <p class="font-body text-[13px] text-muted">Mevcut görsel yüklü. Yeni dosya seçerseniz eskisi silinir.</p>
            </div>
          @else
            <p class="mb-4 font-body text-[13px] text-muted">Görsel yüklenmemiş — varsayılan anasayfa görseli kullanılır.</p>
          @endif
          <label for="spotlight_image" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Spotlight Görseli</label>
          <input type="file" id="spotlight_image" name="spotlight_image" accept="{{ \App\Support\ImageUploadRules::acceptAttribute() }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('spotlight_image') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="spotlight_title" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Spotlight Başlık (Alıntı)</label>
          <input type="text" id="spotlight_title" name="spotlight_title" value="{{ old('spotlight_title', $setting->spotlight_title) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="{{ \App\Models\Setting::DEFAULT_SPOTLIGHT_TITLE }}">
          @error('spotlight_title') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="spotlight_subtitle" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Spotlight Alt Yazı (Marka)</label>
          <input type="text" id="spotlight_subtitle" name="spotlight_subtitle" value="{{ old('spotlight_subtitle', $setting->spotlight_subtitle) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="{{ \App\Models\Setting::DEFAULT_SPOTLIGHT_SUBTITLE }}">
          <p class="mt-1.5 font-body text-[12px] text-muted">Sağ taraftaki kısa marka / basın yazısı (ör. DESIGN).</p>
          @error('spotlight_subtitle') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Anasayfa — Atölye Bandı</h3>
        <p class="mt-1 font-body text-[12px] text-muted">Video-band alanındaki geniş arka plan görseli.</p>
      </div>
      <div class="grid gap-5 p-5">
        @if ($setting->hasCustomBandImage())
          <div class="flex items-center gap-4">
            <img src="{{ $setting->bandImageUrl() }}" alt="Atölye band görseli" class="h-24 w-auto max-w-[320px] rounded-lg border border-ink/10 bg-cream object-cover p-1">
            <p class="font-body text-[13px] text-muted">Mevcut görsel yüklü. Yeni dosya seçerseniz eskisi silinir.</p>
          </div>
        @else
          <p class="font-body text-[13px] text-muted">Görsel yüklenmemiş — varsayılan atölye görseli kullanılır.</p>
        @endif
        <div>
          <label for="band_image" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Atölye Band Görseli</label>
          <input type="file" id="band_image" name="band_image" accept="{{ \App\Support\ImageUploadRules::acceptAttribute() }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('band_image') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Anasayfa — Ekip Notu</h3>
        <p class="mt-1 font-body text-[12px] text-muted">Ekip notu bölümünün başlık, metin ve görseli. Boş bırakılırsa varsayılan içerik gösterilir.</p>
      </div>
      <div class="grid gap-5 p-5 md:grid-cols-2">
        <div class="md:col-span-2">
          @if ($setting->hasCustomTeamNoteImage())
            <div class="mb-4 flex items-center gap-4">
              <img src="{{ $setting->teamNoteImageUrl() }}" alt="Ekip notu görseli" class="h-24 w-auto max-w-[280px] rounded-lg border border-ink/10 bg-cream object-cover p-1">
              <p class="font-body text-[13px] text-muted">Mevcut görsel yüklü. Yeni dosya seçerseniz eskisi silinir.</p>
            </div>
          @else
            <p class="mb-4 font-body text-[13px] text-muted">Görsel yüklenmemiş — varsayılan ekip görseli kullanılır.</p>
          @endif
          <label for="team_note_image" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ekip Notu Görseli</label>
          <input type="file" id="team_note_image" name="team_note_image" accept="{{ \App\Support\ImageUploadRules::acceptAttribute() }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('team_note_image') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="team_note_title" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ekip Notu Başlık</label>
          <input type="text" id="team_note_title" name="team_note_title" value="{{ old('team_note_title', $setting->team_note_title) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="{{ \App\Models\Setting::DEFAULT_TEAM_NOTE_TITLE }}">
          @error('team_note_title') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="team_note_description" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ekip Notu Metni</label>
          <textarea id="team_note_description" name="team_note_description" rows="5"
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                    placeholder="{{ \App\Models\Setting::DEFAULT_TEAM_NOTE_DESCRIPTION }}">{{ old('team_note_description', $setting->team_note_description) }}</textarea>
          <p class="mt-1.5 font-body text-[12px] text-muted">Her satır ayrı paragraf olarak gösterilir. Boş bırakılırsa varsayılan metin kullanılır.</p>
          @error('team_note_description') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Sipariş Bildirimleri</h3>
      </div>
      <div class="grid gap-5 p-5">
        <p class="font-body text-[13px] leading-relaxed text-muted">
          Yeni sipariş geldiğinde bu adreslere <strong>“Yeni Sipariş Aldınız”</strong> bildirimi gider. En fazla 4 e-posta adresi girebilirsiniz. Boş bırakılan alanlar yok sayılır.
        </p>
        @php
          $notificationEmails = old('order_notification_emails', $setting->order_notification_emails ?? []);
          if (! is_array($notificationEmails)) {
              $notificationEmails = [];
          }
        @endphp
        <div class="grid gap-4 md:grid-cols-2">
          @foreach (range(0, 3) as $index)
            <div>
              <label for="order_notification_email_{{ $index }}" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Bildirim E-postası {{ $index + 1 }}</label>
              <input type="email"
                     id="order_notification_email_{{ $index }}"
                     name="order_notification_emails[]"
                     value="{{ $notificationEmails[$index] ?? '' }}"
                     class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                     placeholder="ornek@firma.com">
              @error('order_notification_emails.'.$index)
                <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p>
              @enderror
            </div>
          @endforeach
        </div>
        @error('order_notification_emails') <p class="font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">İletişim Bilgileri</h3>
      </div>
      <div class="grid gap-5 p-5 md:grid-cols-2">
        <div>
          <label for="email" class="mb-1.5 block font-body text-[13px] font-bold text-ink">E-posta</label>
          <input type="email" id="email" name="email" value="{{ old('email', $setting->email) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('email') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="mobile_phone" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Cep Telefonu</label>
          <input type="text" id="mobile_phone" name="mobile_phone" value="{{ old('mobile_phone', $setting->mobile_phone) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="0532 123 45 67">
          @error('mobile_phone') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="business_phone" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İş Telefonu</label>
          <input type="text" id="business_phone" name="business_phone" value="{{ old('business_phone', $setting->business_phone) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('business_phone') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="whatsapp_phone" class="mb-1.5 block font-body text-[13px] font-bold text-ink">WhatsApp Numarası</label>
          <input type="text" id="whatsapp_phone" name="whatsapp_phone" value="{{ old('whatsapp_phone', $setting->whatsapp_phone) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="905321234567">
          @error('whatsapp_phone') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="address" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Adres</label>
          <textarea id="address" name="address" rows="3"
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">{{ old('address', $setting->address) }}</textarea>
          @error('address') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="short_info" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kısa Bilgi (Footer)</label>
          <textarea id="short_info" name="short_info" rows="2"
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                    placeholder="Footer altında gösterilecek kısa açıklama">{{ old('short_info', $setting->short_info) }}</textarea>
          @error('short_info') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Sosyal Medya</h3>
      </div>
      <div class="grid gap-5 p-5 md:grid-cols-2">
        <div>
          <label for="instagram_url" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Instagram</label>
          <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="https://instagram.com/...">
          @error('instagram_url') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="twitter_url" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Twitter / X</label>
          <input type="url" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $setting->twitter_url) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('twitter_url') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="facebook_url" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Facebook</label>
          <input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('facebook_url') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <button type="submit" class="inline-flex w-full max-w-xs items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      Ayarları Kaydet
    </button>
  </form>
@endsection

@section('scripts')
<script>
  (function () {
    const discountEnabled = document.getElementById('discount_enabled');
    const discountFields = document.getElementById('discount-fields');
    const discountType = document.getElementById('discount_type');
    const discountValue = document.getElementById('discount_value');
    const discountValueHint = document.getElementById('discount_value_hint');
    const shippingMode = document.getElementById('shipping_mode');
    const shippingPaidFields = document.getElementById('shipping-paid-fields');
    const freeLimitEnabled = document.getElementById('shipping_free_limit_enabled');
    const freeLimitInput = document.getElementById('shipping_free_limit');

    function toggleDiscountFields() {
      if (!discountEnabled || !discountFields) return;
      discountFields.style.display = discountEnabled.checked ? 'grid' : 'none';
    }

    function toggleDiscountValueLimit() {
      if (!discountType || !discountValue) return;

      const isPercent = discountType.value === 'percent';

      if (isPercent) {
        discountValue.max = '100';
        discountValueHint?.classList.remove('hidden');
      } else {
        discountValue.removeAttribute('max');
        discountValueHint?.classList.add('hidden');
      }

      if (isPercent && discountValue.value !== '' && Number(discountValue.value) > 100) {
        discountValue.value = '100';
      }
    }

    function toggleShippingFields() {
      if (!shippingMode || !shippingPaidFields) return;
      shippingPaidFields.style.display = shippingMode.value === 'paid' ? 'grid' : 'none';
    }

    function toggleFreeLimitInput() {
      if (!freeLimitEnabled || !freeLimitInput) return;
      freeLimitInput.disabled = !freeLimitEnabled.checked;
      freeLimitInput.closest('div')?.classList.toggle('opacity-50', !freeLimitEnabled.checked);
    }

    discountEnabled?.addEventListener('change', toggleDiscountFields);
    discountType?.addEventListener('change', toggleDiscountValueLimit);
    discountValue?.addEventListener('input', toggleDiscountValueLimit);
    shippingMode?.addEventListener('change', toggleShippingFields);
    freeLimitEnabled?.addEventListener('change', toggleFreeLimitInput);

    toggleDiscountFields();
    toggleDiscountValueLimit();
    toggleShippingFields();
    toggleFreeLimitInput();
  })();
</script>
@endsection
