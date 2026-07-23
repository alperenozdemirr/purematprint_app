<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Newsletter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsletterBroadcastRequest;
use App\Mail\NewsletterBroadcastMail;
use App\Models\Newsletter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $subscribers = Newsletter::query()
            ->latest()
            ->paginate(20);

        return view('admin.newsletter-list', [
            'subscribers' => $subscribers,
            'subscriberCount' => Newsletter::query()->count(),
        ]);
    }

    public function broadcast(NewsletterBroadcastRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $emails = Newsletter::query()->pluck('email');

        if ($emails->isEmpty()) {
            return back()->with('error', 'Gönderilecek abone bulunmuyor.');
        }

        $sent = 0;
        $failed = 0;

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new NewsletterBroadcastMail(
                    $validated['subject'],
                    $validated['content'],
                ));
                $sent++;
            } catch (\Throwable $exception) {
                $failed++;
                report($exception);
            }
        }

        $message = "{$sent} aboneye bülten gönderildi.";
        if ($failed > 0) {
            $message .= " {$failed} gönderim başarısız oldu.";
        }

        return back()->with('success', $message);
    }

    public function destroy(int $id): RedirectResponse
    {
        Newsletter::query()->findOrFail($id)->delete();

        return back()->with('success', 'Abone listeden kaldırıldı.');
    }
}
