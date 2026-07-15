<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hoş Geldiniz</title>
</head>
<body style="margin:0;padding:0;background:#faf6ee;font-family:Arial,sans-serif;color:#1a1a1a;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#faf6ee;padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#fffdf8;border:3px solid #1a1a1a;">
          <tr>
            <td style="padding:28px 24px;border-bottom:3px solid #1a1a1a;background:#354e9c;color:#faf6ee;">
              <p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">PureMatPrint</p>
              <h1 style="margin:10px 0 0;font-size:26px;line-height:1.2;">Hoş Geldiniz!</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 24px;">
              <p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:#1a1a1a;">
                Merhaba <strong>{{ $name }}</strong>,
              </p>
              <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:#5e5a54;">
                PureMatPrint ailesine katıldığınız için teşekkür ederiz. Hesabınız başarıyla oluşturuldu; artık siparişlerinizi takip edebilir, adreslerinizi kaydedebilir ve hızlıca alışverişe başlayabilirsiniz.
              </p>

              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;background:#fbf8f1;border:2px solid #1a1a1a;">
                <tr>
                  <td style="padding:18px 16px;">
                    <p style="margin:0 0 10px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#354e9c;">Hesabınızla neler yapabilirsiniz?</p>
                    <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:#5e5a54;">✓ Sipariş geçmişinizi görüntüleyin</p>
                    <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:#5e5a54;">✓ Kayıtlı adreslerle hızlı ödeme yapın</p>
                    <p style="margin:0;font-size:14px;line-height:1.6;color:#5e5a54;">✓ Teslim edilen ürünleri değerlendirin</p>
                  </td>
                </tr>
              </table>

              <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 24px;">
                <tr>
                  <td style="padding-right:10px;">
                    <a href="{{ route('shops') }}" style="display:inline-block;padding:14px 22px;background:#5a544e;color:#faf6ee;font-size:13px;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:0.06em;border:2px solid #1a1a1a;">Alışverişe Başla</a>
                  </td>
                  <td>
                    <a href="{{ route('loginPage') }}" style="display:inline-block;padding:14px 22px;background:#fffdf8;color:#1a1a1a;font-size:13px;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:0.06em;border:2px solid #1a1a1a;">Giriş Yap</a>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 8px;font-size:13px;line-height:1.6;color:#5e5a54;">
                500₺ ve üzeri siparişlerinizde <strong>ücretsiz kargo</strong> avantajından yararlanabilirsiniz.
              </p>
              <p style="margin:0;font-size:13px;line-height:1.6;color:#5e5a54;">
                Sorularınız için bizimle iletişime geçmekten çekinmeyin. İyi alışverişler dileriz!
              </p>
            </td>
          </tr>
          <tr>
            <td style="padding:18px 24px;border-top:3px solid #1a1a1a;background:#2a2826;color:#faf6ee;">
              <p style="margin:0;font-size:12px;line-height:1.6;opacity:0.85;">© {{ date('Y') }} PureMatPrint — Baskı & Tabela Stüdyosu</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
