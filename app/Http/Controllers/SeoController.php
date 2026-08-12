<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File as FileFacade;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SeoController extends Controller
{
    public function robots(): Response
    {
        return response()
            ->view('seo.robots')
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function sitemap(): Response
    {
        $urls = [];

        $urls[] = $this->entry(route('index'), now(), 'daily', '1.0');
        $urls[] = $this->entry(route('shops'), $this->maxTimestamp(Product::query()->where('status', Status::ACTIVE)), 'daily', '0.9');

        Category::query()
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('id')
            ->get(['slug', 'updated_at', 'created_at'])
            ->each(function (Category $category) use (&$urls) {
                $urls[] = $this->entry(
                    route('categoryShow', $category->slug),
                    $category->updated_at ?? $category->created_at,
                    'weekly',
                    '0.85',
                );
            });

        $urls[] = $this->entry(route('collectionList'), $this->maxTimestamp(Collection::query()->where('status', Status::ACTIVE)), 'daily', '0.85');
        $urls[] = $this->entry(route('blogList'), $this->maxTimestamp(Blog::query()), 'daily', '0.85');
        $urls[] = $this->entry(route('contact'), now(), 'weekly', '0.9');
        $urls[] = $this->entry(route('faq'), now(), 'weekly', '0.7');
        $urls[] = $this->entry(route('about'), now(), 'monthly', '0.6');
        $urls[] = $this->entry(route('shippingInfo'), now(), 'monthly', '0.6');
        $urls[] = $this->entry(route('agreements'), now(), 'yearly', '0.4');
        $urls[] = $this->entry(route('cookies'), now(), 'yearly', '0.4');

        Product::query()
            ->where('status', Status::ACTIVE)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('id')
            ->get(['slug', 'updated_at', 'created_at'])
            ->each(function (Product $product) use (&$urls) {
                $urls[] = $this->entry(
                    route('shopDetail', $product->slug),
                    $product->updated_at ?? $product->created_at,
                    'weekly',
                    '0.8',
                );
            });

        Collection::query()
            ->where('status', Status::ACTIVE)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('id')
            ->get(['slug', 'updated_at', 'created_at'])
            ->each(function (Collection $collection) use (&$urls) {
                $urls[] = $this->entry(
                    route('collectionShow', $collection->slug),
                    $collection->updated_at ?? $collection->created_at,
                    'weekly',
                    '0.8',
                );
            });

        Blog::query()
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('id')
            ->get(['slug', 'updated_at', 'created_at'])
            ->each(function (Blog $blog) use (&$urls) {
                $urls[] = $this->entry(
                    route('blogShow', $blog->slug),
                    $blog->updated_at ?? $blog->created_at,
                    'weekly',
                    '0.8',
                );
            });

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function favicon(): BinaryFileResponse|Response
    {
        $setting = Setting::current()->loadMissing('logo');

        if ($setting->logo !== null) {
            $path = storage_path('app/public/'.$setting->logo->storagePath());

            if (FileFacade::isFile($path)) {
                return response()->file($path, [
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        $fallback = public_path('favicon-32.png');

        if (FileFacade::isFile($fallback)) {
            return response()->file($fallback, [
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        return response('', 404);
    }

    /**
     * @return array{loc: string, lastmod: string, changefreq: string, priority: string}
     */
    private function entry(string $loc, ?\DateTimeInterface $timestamp, string $changefreq, string $priority): array
    {
        return [
            'loc' => $loc,
            'lastmod' => ($timestamp ?? now())->format(DATE_ATOM),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    private function maxTimestamp($query): \DateTimeInterface
    {
        $maxUpdated = (clone $query)->max('updated_at');
        $maxCreated = (clone $query)->max('created_at');
        $value = $maxUpdated ?? $maxCreated;

        return $value !== null ? Carbon::parse($value) : now();
    }
}
