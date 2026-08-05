<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('user.default.about');
    }

    public function contact(): View
    {
        return view('user.default.contact');
    }

    public function shipping(): View
    {
        return view('user.default.shipping');
    }

    public function agreements(): View
    {
        return view('user.default.agreements');
    }

    public function privacy(): RedirectResponse
    {
        return redirect(route('agreements').'#kvkk-aydinlatma');
    }

    public function cookies(): View
    {
        return view('user.default.cookies');
    }

    public function distanceSales(): RedirectResponse
    {
        return redirect(route('agreements').'#mesafeli-satis-sozlesmesi');
    }
}
