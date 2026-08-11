<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $headline }}</title>
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
              <h1 style="margin:10px 0 0;font-size:24px;line-height:1.2;font-family:{{ $mailFontHeading }}">{{ $headline }}</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 24px;">
              <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">
                Merhaba <strong>{{ $order->user->name }}</strong>,
              </p>
              <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:{{ $mailMuted }};">
                <strong>{{ $order->code }}</strong> numaralı siparişinizde bir güncelleme yapıldı.
              </p>

              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:{{ $mailCream }};border:2px solid {{ $mailInk }};">
                <tr>
                  <td style="padding:18px 16px;">
                    <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:{{ $mailAnnounce }};">Güncelleme</p>
                    <p style="margin:0;font-size:14px;line-height:1.6;color:{{ $mailMuted }};">{{ $headline }}</p>
                    @if (filled($note))
                      <p style="margin:12px 0 0;font-size:14px;line-height:1.6;color:{{ $mailInk }};">
                        <strong>Not:</strong> {{ $note }}
                      </p>
                    @endif
                    @if ($order->design_status)
                      <p style="margin:12px 0 0;font-size:13px;line-height:1.6;color:{{ $mailMuted }};">
                        Tasarım durumu: <strong>{{ $order->design_status->label() }}</strong>
                      </p>
                    @endif
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 20px;">
                <a href="{{ route('orderShow', $order->code) }}"
                   style="display:inline-block;padding:12px 18px;background:{{ $mailAnnounce }};color:{{ $mailOnDark }};text-decoration:none;font-size:13px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border:2px solid {{ $mailInk }};">
                  Sipariş Detayına Git
                </a>
              </p>

              <p style="margin:0;font-size:13px;line-height:1.6;color:{{ $mailMuted }};">
                Sipariş “Hazırlanıyor” durumundayken dosya ve tasarım işlemlerini sipariş detay sayfasından yönetebilirsiniz.
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
