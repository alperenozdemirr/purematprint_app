@include('mail.partials.theme')
<tr>
  <td align="center" style="padding:24px 24px 16px;background:{{ $mailSurface }};">
    <a href="{{ config('app.url') }}" style="text-decoration:none;">
      <img src="{{ \App\Support\MailBranding::logoUrl() }}" alt="PureMatPrint" width="180" style="display:block;width:180px;max-width:100%;height:auto;border:0;">
    </a>
  </td>
</tr>
