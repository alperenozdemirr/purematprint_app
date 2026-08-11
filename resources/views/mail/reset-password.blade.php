<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Şifre Sıfırlama</title>
</head>
<body style="margin:0;padding:0;background:{{ $mailBg }};font-family:{{ $mailFontBody }};color:{{ $mailInk }};">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:{{ $mailBg }};padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:{{ $mailSurface }};border:3px solid {{ $mailInk }};">
          @include('mail.partials.logo-row')
          <tr>
            <td style="padding:28px 24px;border-bottom:3px solid {{ $mailInk }};background:{{ $mailAnnounce }};color:{{ $mailOnDark }};">
              <p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">PureMatPrint</p>
              <h1 style="margin:10px 0 0;font-size:26px;line-height:1.2;font-family:{{ $mailFontHeading }}">Şifre Sıfırlama</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 24px;">
              <p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:{{ $mailInk }};">
                Merhaba <strong>{{ $name }}</strong>,
              </p>
              <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:{{ $mailMuted }};">
                Hesabınız için şifre sıfırlama talebi aldık. Yeni şifrenizi belirlemek için aşağıdaki butona tıklayın.
              </p>

              <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 24px;">
                <tr>
                  <td>
                    <a href="{{ $url }}" style="display:inline-block;padding:14px 22px;background:{{ $mailAction }};color:{{ $mailOnDark }};font-size:13px;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:0.06em;border:2px solid {{ $mailInk }};">Şifremi Sıfırla</a>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 16px;font-size:13px;line-height:1.6;color:{{ $mailMuted }};">
                Bu bağlantı <strong>{{ $expire }} dakika</strong> geçerlidir. Süresi dolduktan sonra yeni bir sıfırlama talebi oluşturmanız gerekir.
              </p>
              <p style="margin:0 0 16px;font-size:13px;line-height:1.6;color:{{ $mailMuted }};">
                Buton çalışmıyorsa aşağıdaki bağlantıyı tarayıcınıza kopyalayın:
              </p>
              <p style="margin:0 0 16px;font-size:12px;line-height:1.6;word-break:break-all;color:{{ $mailAnnounce }};">
                {{ $url }}
              </p>
              <p style="margin:0;font-size:13px;line-height:1.6;color:{{ $mailMuted }};">
                Bu talebi siz yapmadıysanız bu e-postayı yok sayabilirsiniz; şifreniz değiştirilmeyecektir.
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
