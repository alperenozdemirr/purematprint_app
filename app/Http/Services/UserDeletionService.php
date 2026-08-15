<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\ContentType;
use App\Enums\Status;
use App\Models\Address;
use App\Models\Comment;
use App\Models\EmailVerification;
use App\Models\File;
use App\Models\Payment;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class UserDeletionService
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function hasOrderHistory(User $user): bool
    {
        return $user->orders()->exists();
    }

    public function deactivate(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $this->clearPersonalDataOnDeactivation($user);

            if ($user->status !== Status::PASSIVE) {
                $user->update(['status' => Status::PASSIVE]);
            }
        });
    }

    public function clearPersonalDataOnDeactivation(User $user): void
    {
        ShoppingCart::query()
            ->where('user_id', $user->id)
            ->delete();

        Comment::query()
            ->where('user_id', $user->id)
            ->update(['is_visible' => false]);

        $user->tokens()->delete();
    }

    public function deleteFully(User $user): void
    {
        if ($this->hasOrderHistory($user)) {
            throw new RuntimeException('Sipariş geçmişi olan kullanıcı silinemez. Pasife alın.');
        }

        DB::transaction(function () use ($user): void {
            Comment::query()
                ->where('user_id', $user->id)
                ->with('images')
                ->get()
                ->each(function (Comment $comment): void {
                    foreach ($comment->images as $image) {
                        $this->fileService->imageDelete($image->id, ContentType::COMMENT);
                    }

                    $comment->delete();
                });

            ShoppingCart::query()->where('user_id', $user->id)->delete();
            Payment::query()->where('user_id', $user->id)->delete();
            Address::query()->where('user_id', $user->id)->delete();
            EmailVerification::query()->where('email', $user->email)->delete();

            File::query()
                ->where('user_id', $user->id)
                ->pluck('id')
                ->each(fn (int $fileId) => $this->fileService->imageDelete($fileId, ContentType::USER));

            if ($user->image_id) {
                $this->fileService->imageDelete($user->image_id, ContentType::USER);
            }

            $user->tokens()->delete();
            $user->delete();
        });
    }
}
