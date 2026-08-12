<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Str;

class Seo
{
    public const DEFAULT_ROBOTS = 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';

    public const NOINDEX_FOLLOW = 'noindex,follow';

    public const NOINDEX_NOFOLLOW = 'noindex,nofollow';

    public static function siteName(): string
    {
        $name = trim((string) config('app.name', ''));

        if ($name === '' || strcasecmp($name, 'laravel') === 0) {
            return 'PureMatPrint';
        }

        return $name;
    }

    public static function documentTitle(?string $pageTitle): string
    {
        $pageTitle = trim(strip_tags((string) $pageTitle));
        $siteName = self::siteName();

        if ($pageTitle === '' || $pageTitle === $siteName) {
            return $siteName;
        }

        return $pageTitle.' | '.$siteName;
    }

    public static function defaultDescription(?Setting $setting = null): string
    {
        $setting ??= Setting::current();
        $short = trim(strip_tags((string) ($setting->short_info ?? '')));

        if ($short !== '') {
            return self::limitDescription($short);
        }

        return self::limitDescription(
            'PureMatPrint ile tabela, dijital baskı ve kurumsal kimlik ürünlerini online sipariş edin. Hızlı üretim, kaliteli baskı ve güvenilir teslimat.',
        );
    }

    public static function limitDescription(string $text, int $limit = 160): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'))) ?? '');

        return Str::limit($text, $limit, '…');
    }

    public static function plainText(?string $html, int $limit = 160): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return self::limitDescription($html, $limit);
    }

    public static function absoluteUrl(?string $url): string
    {
        if ($url === null || $url === '') {
            return url('/');
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return url($url);
    }

    public static function defaultOgImage(?Setting $setting = null): string
    {
        $setting ??= Setting::current()->loadMissing('logo');

        return self::absoluteUrl($setting->logoUrl());
    }

    /**
     * @return list<string>
     */
    public static function sameAsLinks(?Setting $setting = null): array
    {
        $setting ??= Setting::current();

        return collect([
            $setting->instagram_url,
            $setting->facebook_url,
            $setting->twitter_url,
            $setting->whatsappLink(),
        ])
            ->filter(fn ($url) => filled($url))
            ->map(fn ($url) => (string) $url)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public static function organizationSchema(?Setting $setting = null): array
    {
        $setting ??= Setting::current()->loadMissing('logo');
        $siteName = self::siteName();

        $schema = [
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => url('/'),
            'logo' => self::defaultOgImage($setting),
        ];

        $sameAs = self::sameAsLinks($setting);

        if ($sameAs !== []) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }

    /**
     * @return array<string, mixed>
     */
    public static function websiteSchema(): array
    {
        $searchTarget = route('shops').'?q={search_term_string}';

        return [
            '@type' => 'WebSite',
            'name' => self::siteName(),
            'url' => url('/'),
            'inLanguage' => 'tr-TR',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $searchTarget,
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function localBusinessSchema(?Setting $setting = null): array
    {
        $setting ??= Setting::current()->loadMissing('logo');
        $siteName = self::siteName();
        $contactUrl = route('contact');

        $addressLine = trim((string) ($setting->address ?? ''));
        if ($addressLine === '') {
            $addressLine = 'Maltepe Mah. Litros Yolu Sk. D Blok No: 2-4D/Z16, Zeytinburnu/İstanbul, Türkiye';
        }

        $email = trim((string) ($setting->email ?? ''));
        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'hello@purematprint.com';
        }

        $telephone = self::formatTelephone(
            $setting->mobile_phone
            ?? $setting->business_phone
            ?? $setting->whatsapp_phone
            ?? '+905364624480',
        );

        $postalAddress = [
            '@type' => 'PostalAddress',
            'streetAddress' => $addressLine,
            'addressCountry' => 'TR',
        ];

        if (preg_match('/,\s*([^,\/]+)\/([^,\/]+)/u', $addressLine, $matches)) {
            $postalAddress['addressLocality'] = trim($matches[1]);
            $postalAddress['addressRegion'] = trim($matches[2]);
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => self::absoluteUrl($contactUrl).'#localbusiness',
            'name' => $siteName,
            'legalName' => 'GENÇ PRINT REKLAM ANONİM ŞİRKETİ',
            'url' => self::absoluteUrl($contactUrl),
            'logo' => self::defaultOgImage($setting),
            'image' => self::defaultOgImage($setting),
            'email' => $email,
            'address' => $postalAddress,
            'inLanguage' => 'tr-TR',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'email' => $email,
                'availableLanguage' => ['tr-TR', 'tr'],
            ],
        ];

        if ($telephone !== null) {
            $schema['telephone'] = $telephone;
            $schema['contactPoint']['telephone'] = $telephone;
        }

        $sameAs = self::sameAsLinks($setting);
        if ($sameAs !== []) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }

    public static function formatTelephone(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return trim($phone);
        }

        if (str_starts_with($digits, '90') && strlen($digits) >= 12) {
            return '+'.$digits;
        }

        if (str_starts_with($digits, '0')) {
            return '+90'.substr($digits, 1);
        }

        if (strlen($digits) === 10) {
            return '+90'.$digits;
        }

        return '+'.$digits;
    }

    /**
     * @return array<string, mixed>
     */
    public static function layoutSchema(?Setting $setting = null): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                self::organizationSchema($setting),
                self::websiteSchema(),
            ],
        ];
    }

    /**
     * @param  list<array{name: string, url?: string|null}>  $items
     * @return array<string, mixed>
     */
    public static function breadcrumbSchema(array $items): array
    {
        $elements = [];

        foreach ($items as $index => $item) {
            $position = $index + 1;
            $entry = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $item['name'],
            ];

            if (! empty($item['url'])) {
                $entry['item'] = self::absoluteUrl((string) $item['url']);
            }

            $elements[] = $entry;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * @param  iterable<int, array{name: string, url: string}>  $listItems
     * @return array<string, mixed>
     */
    public static function collectionPageSchema(
        string $name,
        string $description,
        string $url,
        int $totalItems,
        int $firstItemPosition,
        iterable $listItems,
    ): array {
        $items = [];

        foreach ($listItems as $index => $item) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $firstItemPosition + $index,
                'url' => self::absoluteUrl($item['url']),
                'name' => $item['name'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'CollectionPage',
                    'name' => $name,
                    'description' => $description,
                    'url' => self::absoluteUrl($url),
                    'inLanguage' => 'tr-TR',
                    'mainEntity' => [
                        '@type' => 'ItemList',
                        'numberOfItems' => $totalItems,
                        'itemListElement' => $items,
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  list<string>  $images
     * @param  array{ratingValue: float|int, reviewCount: int}|null  $aggregateRating
     * @return array<string, mixed>
     */
    public static function productSchema(
        string $name,
        string $description,
        string $canonicalUrl,
        array $images,
        ?string $sku,
        float $price,
        bool $inStock,
        ?array $aggregateRating = null,
    ): array {
        $images = collect($images)
            ->filter(fn ($image) => filled($image))
            ->map(fn ($image) => self::absoluteUrl((string) $image))
            ->unique()
            ->values()
            ->all();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $name,
            'description' => $description,
            'url' => self::absoluteUrl($canonicalUrl),
            'inLanguage' => 'tr-TR',
            'offers' => [
                '@type' => 'Offer',
                'url' => self::absoluteUrl($canonicalUrl),
                'priceCurrency' => 'TRY',
                'price' => number_format($price, 2, '.', ''),
                'availability' => $inStock
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
            ],
        ];

        if ($images !== []) {
            $schema['image'] = count($images) === 1 ? $images[0] : $images;
        }

        if ($sku !== null && $sku !== '') {
            $schema['sku'] = $sku;
        }

        if ($aggregateRating !== null && $aggregateRating['reviewCount'] > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => number_format((float) $aggregateRating['ratingValue'], 1, '.', ''),
                'reviewCount' => (int) $aggregateRating['reviewCount'],
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        return $schema;
    }

    /**
     * @param  iterable<object{is_visible?: bool, rating?: mixed}>  $reviews
     * @return array{ratingValue: float, reviewCount: int}|null
     */
    public static function aggregateRatingFromReviews(iterable $reviews): ?array
    {
        $ratings = collect($reviews)
            ->filter(function ($review) {
                $visible = $review->is_visible ?? true;

                return $visible && $review->rating !== null && (float) $review->rating > 0;
            })
            ->map(fn ($review) => (float) $review->rating);

        if ($ratings->isEmpty()) {
            return null;
        }

        return [
            'ratingValue' => round($ratings->avg(), 1),
            'reviewCount' => $ratings->count(),
        ];
    }

    /**
     * @param  list<string>  $images
     * @return array<string, mixed>
     */
    public static function blogPostingSchema(
        string $headline,
        string $description,
        string $canonicalUrl,
        array $images,
        ?\DateTimeInterface $publishedAt,
        ?\DateTimeInterface $modifiedAt,
        ?Setting $setting = null,
    ): array {
        $setting ??= Setting::current()->loadMissing('logo');
        $siteName = self::siteName();
        $images = collect($images)
            ->filter(fn ($image) => filled($image))
            ->map(fn ($image) => self::absoluteUrl((string) $image))
            ->unique()
            ->values()
            ->all();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $headline,
            'description' => $description,
            'inLanguage' => 'tr-TR',
            'url' => self::absoluteUrl($canonicalUrl),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => self::absoluteUrl($canonicalUrl),
            ],
            'author' => [
                '@type' => 'Organization',
                'name' => $siteName,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => self::defaultOgImage($setting),
                ],
            ],
        ];

        if ($publishedAt !== null) {
            $schema['datePublished'] = $publishedAt->format(DATE_ATOM);
        }

        if ($modifiedAt !== null) {
            $schema['dateModified'] = $modifiedAt->format(DATE_ATOM);
        }

        if ($images !== []) {
            $schema['image'] = count($images) === 1 ? $images[0] : $images;
        }

        return $schema;
    }

    /**
     * @param  list<array{question: string, answer: string}>  $faqs
     * @return array<string, mixed>|null
     */
    public static function faqPageSchema(array $faqs): ?array
    {
        if ($faqs === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn (array $faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ])->all(),
        ];
    }

    public static function atomTimestamp(?\DateTimeInterface $updatedAt, ?\DateTimeInterface $createdAt = null): string
    {
        $date = $updatedAt ?? $createdAt ?? now();

        return $date->format(DATE_ATOM);
    }
}
