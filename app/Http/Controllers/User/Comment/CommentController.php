<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Comment;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CommentStoreRequest;
use App\Models\Comment;
use App\Models\OrderDetail;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $orderDetail = OrderDetail::query()
            ->with('order')
            ->findOrFail((int) $validated['order_detail_id']);

        if ($orderDetail->order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($orderDetail->order->status !== OrderStatus::COMPLETED) {
            return back()->with('error', 'Yalnızca teslim edilmiş siparişlerdeki ürünleri değerlendirebilirsiniz.');
        }

        $alreadyReviewed = Comment::query()
            ->where('order_detail_id', $orderDetail->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Bu ürün için zaten değerlendirme yaptınız.');
        }

        Comment::create([
            'product_id' => $orderDetail->product_id,
            'user_id' => auth()->id(),
            'order_detail_id' => $orderDetail->id,
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'is_visible' => false,
        ]);

        return redirect()
            ->route('orderShow', $orderDetail->order->code)
            ->with('success', 'Değerlendirmeniz alındı. Onaylandıktan sonra yayınlanacaktır.');
    }
}
