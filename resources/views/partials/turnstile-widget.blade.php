@php
    $siteKey = config('turnstile.site_key');
    $turnstileEnabled = config('turnstile.enabled') && filled($siteKey);
@endphp

@if ($turnstileEnabled)
    @once
        @if ($loadScriptInline ?? false)
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @else
            @push('scripts')
                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            @endpush
        @endif
    @endonce

    <div @class([
        'flex justify-center',
        $wrapperClass ?? '',
    ])>
        <div
            class="cf-turnstile"
            data-sitekey="{{ $siteKey }}"
            data-theme="{{ $theme ?? 'light' }}"
        ></div>
    </div>

    @if (($showError ?? true) && $errors->has('cf-turnstile-response'))
        <p @class([
            'font-body text-[12px] font-medium',
            $errorClass ?? 'mt-1.5 text-center text-announce',
        ])>{{ $errors->first('cf-turnstile-response') }}</p>
    @endif
@endif
