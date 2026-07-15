<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-posta Doğrulama</title>
</head>
<body style="margin:0;padding:0;background:#faf6ee;font-family:Arial,sans-serif;color:#1a1a1a;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#faf6ee;padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#fffdf8;border:3px solid #1a1a1a;">
          <tr>
            <td style="padding:28px 24px;border-bottom:3px solid #1a1a1a;background:#2a2826;color:#faf6ee;">
              <p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">PureMatPrint</p>
              <h1 style="margin:10px 0 0;font-size:24px;line-height:1.2;">E-posta Doğrulama</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 24px;">
              <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#5e5a54;">
                Kayıt işleminizi tamamlamak için aşağıdaki 6 haneli doğrulama kodunu kullanın.
              </p>
              <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#5e5a54;">Doğrulama Kodu</p>
              <p style="margin:0 0 20px;font-size:34px;font-weight:700;letter-spacing:0.28em;color:#354e9c;">{{ $code }}</p>
              <p style="margin:0;font-size:13px;line-height:1.6;color:#5e5a54;">
                Kod 10 dakika geçerlidir. Bu işlemi siz yapmadıysanız bu e-postayı yok sayabilirsiniz.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
