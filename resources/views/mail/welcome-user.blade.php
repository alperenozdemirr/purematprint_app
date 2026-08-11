<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hoş Geldiniz</title>
</head>
@include('mail.partials.theme')
<body style="margin:0;padding:0;background:{{ $mailBg }};font-family:{{ $mailFontBody }};color:{{ $mailInk }};">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:{{ $mailBg }};padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:{{ $mailSurface }};border:3px solid {{ $mailInk }};">
          @include('mail.partials.logo-row')
          <tr>
            <td style="padding:28px 24px;border-bottom:3px solid {{ $mailInk }};background:{{ $mailAnnounce }};color:{{ $mailOnDark }};">
              <p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">PureMatPrint</p>
              <h1 style="margin:10px 0 0;font-size:26px;line-height:1.2;font-family:{{ $mailFontHeading }}">Hoş Geldiniz!</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 24px;">
              <p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:{{ $mailInk }};">
                Merhaba <strong>{{ $name }}</strong>,
              </p>
              <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:{{ $mailMuted }};">
                PureMatPrint ailesine katıldığınız için teşekkür ederiz. Hesabınız başarıyla oluşturuldu; artık siparişlerinizi takip edebilir, adreslerinizi kaydedebilir ve hızlıca alışverişe başlayabilirsiniz.
              </p>

              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;background:{{ $mailCream }};border:2px solid {{ $mailInk }};">
                <tr>
                  <td style="padding:18px 16px;">
                    <p style="margin:0 0 10px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:{{ $mailAnnounce }};">Hesabınızla neler yapabilirsiniz?</p>
                    <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:{{ $mailMuted }};">✓ Sipariş geçmişinizi görüntüleyin</p>
                    <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:{{ $mailMuted }};">✓ Kayıtlı adreslerle hızlı ödeme yapın</p>
                    <p style="margin:0;font-size:14px;line-height:1.6;color:{{ $mailMuted }};">✓ Teslim edilen ürünleri değerlendirin</p>
                  </td>
                </tr>
              </table>

              <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 24px;">
                <tr>
                  <td style="padding-right:10px;">
                    <a href="{{ route('shops') }}" style="display:inline-block;padding:14px 22px;background:{{ $mailAction }};color:{{ $mailOnDark }};font-size:13px;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:0.06em;border:2px solid {{ $mailInk }};">Alışverişe Başla</a>
                  </td>
                  <td>
                    <a href="{{ route('loginPage') }}" style="display:inline-block;padding:14px 22px;background:{{ $mailSurface }};color:{{ $mailInk }};font-size:13px;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:0.06em;border:2px solid {{ $mailInk }};">Giriş Yap</a>
                  </td>
                </tr>
              </table>

              @if ($shippingPromoSentence)
              <p style="margin:0 0 8px;font-size:13px;line-height:1.6;color:{{ $mailMuted }};">
                {{ $shippingPromoSentence }}
              </p>
              @endif
              <p style="margin:0;font-size:13px;line-height:1.6;color:{{ $mailMuted }};">
                Sorularınız için bizimle iletişime geçmekten çekinmeyin. İyi alışverişler dileriz!
              </p>
            </td>
          </tr>
          @include('mail.partials.footer')
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
