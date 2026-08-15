<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\ContentType;
use App\Enums\Status;
use App\Models\Comment;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductDeletionService
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function hasOrderHistory(Product $product): bool
    {
        return OrderDetail::query()
            ->where('product_id', $product->id)
            ->exists();
    }

    public function deactivate(Product $product): void
    {
        $product->update([
            'status' => Status::PASSIVE,
            'featured_status' => false,
            'introduction_status' => false,
        ]);
    }

    public function deleteFully(Product $product): void
    {
        if ($this->hasOrderHistory($product)) {
            throw new RuntimeException('Sipariş geçmişi olan ürün silinemez. Pasife alın.');
        }

        DB::transaction(function () use ($product): void {
            ShoppingCart::query()
                ->where('product_id', $product->id)
                ->delete();

            $product->collections()->detach();

            Comment::query()
                ->where('product_id', $product->id)
                ->with('images')
                ->get()
                ->each(function (Comment $comment): void {
                    foreach ($comment->images as $image) {
                        $this->fileService->imageDelete($image->id, ContentType::COMMENT);
                    }

                    $comment->delete();
                });

            foreach ($product->images as $image) {
                $this->fileService->imageDelete($image->id, ContentType::PRODUCT);
            }

            $product->delete();
        });
    }
}
