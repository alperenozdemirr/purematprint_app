<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CommentUpdateRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = Comment::query()
            ->with([
                'user',
                'product',
                'orderDetail.order',
            ])
            ->latest()
            ->paginate(15);

        return view('admin.comment-list', compact('comments'));
    }

    public function update(CommentUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $comment = Comment::query()->findOrFail((int) $validated['id']);

        $comment->update([
            'is_visible' => (bool) $validated['is_visible'],
        ]);

        $message = $comment->is_visible
            ? 'Yorum onaylandı ve yayına alındı.'
            : 'Yorum gizlendi.';

        return back()->with('success', $message);
    }

    public function destroy(int $id): RedirectResponse
    {
        Comment::query()->findOrFail($id)->delete();

        return redirect()->route('admin.commentList')->with('success', 'Yorum silindi.');
    }
}
