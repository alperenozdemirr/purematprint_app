<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kuyruk Test</title>
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
              <h1 style="margin:0;font-size:24px;line-height:1.2;font-family:{{ $mailFontHeading }}">Kuyruk testi başarılı</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 24px;">
              <p style="margin:0 0 12px;line-height:1.6;color:{{ $mailMuted }};">
                Bu e-posta <strong>SendTestEmailJob</strong> kuyruk job'u ile gönderildi.
              </p>
              <p style="margin:0;line-height:1.6;color:{{ $mailMuted }};">
                Gönderim zamanı: <strong>{{ $sentAt }}</strong>
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
