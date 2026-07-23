<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Newsletter;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\NewsletterSubscribeRequest;
use App\Models\Newsletter;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function store(NewsletterSubscribeRequest $request): RedirectResponse
    {
        Newsletter::create([
            'email' => $request->validated('email'),
        ]);

        return back()->with('newsletter_success', 'Bültene başarıyla abone oldunuz. Teşekkürler!');
    }
}
