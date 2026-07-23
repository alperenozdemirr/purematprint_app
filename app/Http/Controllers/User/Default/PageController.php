<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Default;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('user.default.about');
    }

    public function contact(): View
    {
        return view('user.default.contact', [
            'setting' => Setting::current(),
        ]);
    }

    public function shipping(): View
    {
        return view('user.default.shipping', [
            'setting' => Setting::current(),
        ]);
    }

    public function privacy(): View
    {
        return view('user.default.privacy');
    }

    public function cookies(): View
    {
        return view('user.default.cookies');
    }

    public function distanceSales(): View
    {
        return view('user.default.distance-sales');
    }
}
