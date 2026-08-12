<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sipariş İptal Bildirimi</title>
</head>
<body style="margin:0;padding:0;background:{{ $mailBg }};font-family:{{ $mailFontBody }};color:{{ $mailInk }};">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:{{ $mailBg }};padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:{{ $mailSurface }};border:3px solid {{ $mailInk }};">
          @include('mail.partials.logo-row')
          <tr>
            <td style="padding:28px 24px;border-bottom:3px solid {{ $mailInk }};background:{{ $mailAnnounce }};color:{{ $mailOnDark }};">
              <p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">PureMatPrint Admin</p>
              <h1 style="margin:10px 0 0;font-size:26px;line-height:1.2;font-family:{{ $mailFontHeading }}">Sipariş İptal Edildi</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 24px;">
              <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:{{ $mailMuted }};">
                <strong>{{ $order->code }}</strong> numaralı sipariş müşteri tarafından iptal edildi ve ödeme iade süreci başlatıldı.
              </p>

              @if ($refundMessage)
              <p style="margin:0 0 16px;font-size:14px;line-height:1.7;color:{{ $mailMuted }};">
                {{ $refundMessage }}
              </p>
              @endif

              <p style="margin:0 0 16px;font-size:14px;line-height:1.7;color:{{ $mailMuted }};">
                {{ $order->payment?->provider?->refundSettlementNotice() ?? \App\Enums\PaymentProvider::IYZICO->refundSettlementNotice() }}
              </p>

              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:{{ $mailCream }};border:2px solid {{ $mailInk }};">
                <tr>
                  <td style="padding:18px 16px;">
                    <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:{{ $mailAnnounce }};">Müşteri</p>
                    <p style="margin:0 0 6px;font-size:14px;line-height:1.6;color:{{ $mailMuted }};">
                      {{ $order->user?->name ?? '—' }}<br>
                      {{ $order->user?->email ?? '—' }}<br>
                      {{ $order->user?->phone ?? '—' }}
                    </p>
                  </td>
                </tr>
              </table>

              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:{{ $mailCream }};border:2px solid {{ $mailInk }};">
                <tr>
                  <td style="padding:18px 16px;">
                    <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:{{ $mailAnnounce }};">İade Özeti</p>
                    <p style="margin:0 0 6px;font-size:14px;line-height:1.6;color:{{ $mailMuted }};">
                      Ödeme yöntemi: {{ $order->payment?->provider?->label() ?? '—' }}
                    </p>
                    <p style="margin:0 0 12px;font-size:16px;font-weight:700;color:{{ $mailInk }};">
                      İade tutarı: {{ number_format((float) ($order->payment?->paid_amount ?? $order->total), 0, ',', '.') }} ₺
                    </p>
                    @foreach ($order->details as $detail)
                      <p style="margin:0 0 6px;font-size:14px;line-height:1.6;color:{{ $mailMuted }};">
                        {{ $detail->product?->title ?? 'Ürün' }} × {{ $detail->quantity }} — {{ number_format((float) $detail->price * $detail->quantity, 0, ',', '.') }} ₺
                      </p>
                    @endforeach
                  </td>
                </tr>
              </table>

              <table role="presentation" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <a href="{{ route('admin.orderDetailPage', $order->code) }}" style="display:inline-block;padding:14px 22px;background:{{ $mailAction }};color:{{ $mailOnDark }};font-size:13px;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:0.06em;border:2px solid {{ $mailInk }};">Admin Panelde Görüntüle</a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          @include('mail.partials.footer')
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
