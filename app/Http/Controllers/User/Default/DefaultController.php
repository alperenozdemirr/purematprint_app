<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Default;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Company;
use App\Models\Product;
use Illuminate\View\View;

class DefaultController extends Controller
{
    public function index(): View
    {
        $bestsellerProducts = Product::query()
            ->with('images')
            ->where('status', Status::ACTIVE)
            ->where('featured_status', true)
            ->latest()
            ->limit(12)
            ->get();

        if ($bestsellerProducts->isEmpty()) {
            $bestsellerProducts = Product::query()
                ->with('images')
                ->where('status', Status::ACTIVE)
                ->latest()
                ->limit(12)
                ->get();
        }

        $welcomeBanners = Banner::query()
            ->with('image')
            ->orderBy('number')
            ->orderByDesc('id')
            ->get();

        $tickerCompanies = Company::ordered()->get();

        return view('user.default.index', compact('bestsellerProducts', 'welcomeBanners', 'tickerCompanies'));
    }
}
