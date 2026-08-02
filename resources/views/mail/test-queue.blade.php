<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kuyruk Test</title>
</head>
<body style="margin:0;padding:24px;font-family:Arial,sans-serif;background:#faf6ee;color:#1a1a1a;">
  <div style="max-width:520px;margin:0 auto;background:#fffdf8;border:3px solid #1a1a1a;padding:24px;">
    <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#354e9c;">PureMatPrint</p>
    <h1 style="margin:0 0 16px;font-size:24px;">Kuyruk testi başarılı</h1>
    <p style="margin:0 0 12px;line-height:1.6;color:#5e5a54;">
      Bu e-posta <strong>SendTestEmailJob</strong> kuyruk job'u ile gönderildi.
    </p>
    <p style="margin:0;line-height:1.6;color:#5e5a54;">
      Gönderim zamanı: <strong>{{ $sentAt }}</strong>
    </p>
  </div>
</body>
</html>
