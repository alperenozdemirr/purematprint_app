<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $mailSubject }}</title>
</head>
<body style="margin:0;padding:0;background:#faf6ee;font-family:Arial,sans-serif;color:#1a1a1a;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#faf6ee;padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#fffdf8;border:3px solid #1a1a1a;">
          <tr>
            <td style="padding:28px 24px;border-bottom:3px solid #1a1a1a;background:#354e9c;color:#faf6ee;">
              <p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">PureMatPrint Bülten</p>
              <h1 style="margin:10px 0 0;font-size:24px;line-height:1.25;">{{ $mailSubject }}</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 24px;font-size:15px;line-height:1.75;color:#5e5a54;">
              {!! $bodyHtml !!}
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
