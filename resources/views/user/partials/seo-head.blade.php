@php
  use App\Support\Seo;

  $seoPageTitle = trim(strip_tags((string) $__env->yieldContent('title')));
  $seoSiteName = Seo::siteName();
  $seoDocumentTitle = Seo::documentTitle($seoPageTitle);
  $seoDescriptionSection = trim(strip_tags((string) $__env->yieldContent('metaDescription')));
  $seoDescription = $seoDescriptionSection !== '' ? Seo::limitDescription($seoDescriptionSection) : Seo::defaultDescription($siteSetting ?? null);
  $seoCanonicalSection = trim((string) $__env->yieldContent('canonicalUrl'));
  $seoCanonicalUrl = $seoCanonicalSection !== '' ? Seo::absoluteUrl($seoCanonicalSection) : Seo::absoluteUrl(url()->current());
  $seoRobotsSection = trim((string) $__env->yieldContent('metaRobots'));
  $seoRobots = $seoRobotsSection !== '' ? $seoRobotsSection : Seo::DEFAULT_ROBOTS;
  $seoOgType = trim((string) $__env->yieldContent('ogType')) ?: 'website';
  $seoOgImageSection = trim((string) $__env->yieldContent('ogImage'));
  $seoOgImage = Seo::absoluteUrl($seoOgImageSection !== '' ? $seoOgImageSection : Seo::defaultOgImage($siteSetting ?? null));
  $seoFavicon = Seo::absoluteUrl(route('seo.favicon'));
  $seoAppleTouch = $seoFavicon;
  $seoLayoutSchema = Seo::layoutSchema($siteSetting ?? null);
@endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#354e9c">
<title>{{ $seoDocumentTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
<meta name="robots" content="{{ $seoRobots }}">
<link rel="canonical" href="{{ $seoCanonicalUrl }}">
<link rel="icon" href="{{ $seoFavicon }}">
<link rel="apple-touch-icon" href="{{ $seoAppleTouch }}">
<meta property="og:locale" content="tr_TR">
<meta property="og:type" content="{{ $seoOgType }}">
<meta property="og:site_name" content="{{ $seoSiteName }}">
<meta property="og:title" content="{{ $seoDocumentTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoCanonicalUrl }}">
<meta property="og:image" content="{{ $seoOgImage }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoDocumentTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoOgImage }}">
<script type="application/ld+json">@json($seoLayoutSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
@stack('head')
