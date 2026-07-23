<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sipariş Onayı</title>
</head>
<body style="margin:0;padding:0;background:#faf6ee;font-family:Arial,sans-serif;color:#1a1a1a;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#faf6ee;padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#fffdf8;border:3px solid #1a1a1a;">
          <tr>
            <td style="padding:28px 24px;border-bottom:3px solid #1a1a1a;background:#354e9c;color:#faf6ee;">
              <p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">PureMatPrint</p>
              <h1 style="margin:10px 0 0;font-size:26px;line-height:1.2;">Siparişiniz Alındı</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 24px;">
              <p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:#1a1a1a;">
                Merhaba <strong>{{ $order->user->name }}</strong>,
              </p>
              <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:#5e5a54;">
                <strong>{{ $order->code }}</strong> numaralı siparişiniz başarıyla oluşturuldu. Ödemeniz alındı ve siparişiniz hazırlık sürecine alındı.
              </p>

              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fbf8f1;border:2px solid #1a1a1a;">
                <tr>
                  <td style="padding:18px 16px;">
                    <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#354e9c;">Sipariş Özeti</p>
                    @foreach ($order->details as $detail)
                      <p style="margin:0 0 6px;font-size:14px;line-height:1.6;color:#5e5a54;">
                        {{ $detail->product->title }} × {{ $detail->quantity }} — {{ number_format((float) $detail->price * $detail->quantity, 0, ',', '.') }} ₺
                      </p>
                    @endforeach
                    @if ((float) $order->discount_amount > 0)
                      <p style="margin:8px 0 0;font-size:14px;line-height:1.6;color:#5e5a54;">İndirim: −{{ number_format((float) $order->discount_amount, 0, ',', '.') }} ₺</p>
                    @endif
                    @if (! $order->shipping_is_free && (float) $order->shipping_price > 0)
                      <p style="margin:4px 0 0;font-size:14px;line-height:1.6;color:#5e5a54;">Kargo: {{ number_format((float) $order->shipping_price, 0, ',', '.') }} ₺</p>
                    @elseif ($order->shipping_is_free)
                      <p style="margin:4px 0 0;font-size:14px;line-height:1.6;color:#5e5a54;">Kargo: Ücretsiz</p>
                    @endif
                    <p style="margin:12px 0 0;font-size:16px;font-weight:700;color:#1a1a1a;">Toplam: {{ number_format((float) $order->total, 0, ',', '.') }} ₺</p>
                  </td>
                </tr>
              </table>

              @if ($order->address)
              <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#354e9c;">Teslimat Adresi</p>
              <p style="margin:0 0 20px;font-size:14px;line-height:1.7;color:#5e5a54;">
                {{ $order->address->title }}<br>
                {{ $order->address->content }}<br>
                {{ $order->address->county?->name }} / {{ $order->address->city?->name }}
              </p>
              @endif

              <table role="presentation" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <a href="{{ route('orderShow', $order->code) }}" style="display:inline-block;padding:14px 22px;background:#5a544e;color:#faf6ee;font-size:13px;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:0.06em;border:2px solid #1a1a1a;">Siparişi Görüntüle</a>
                  </td>
                </tr>
              </table>
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
