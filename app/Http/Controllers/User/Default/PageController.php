<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Default;

use App\Http\Controllers\Controller;
use App\Models\FaqGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('user.default.about');
    }

    public function faq(): View
    {
        $faqGroups = FaqGroup::query()
            ->with(['faqs' => fn ($query) => $query->ordered()])
            ->ordered()
            ->get()
            ->filter(fn (FaqGroup $group) => $group->faqs->isNotEmpty());

        return view('user.default.faq', compact('faqGroups'));
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
