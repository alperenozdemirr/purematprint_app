<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CheckoutDraftService
{
    public function createDraft(
        User $user,
        int $addressId,
        ?string $note,
        array $invoiceAttributes,
        array $summary,
        iterable $cartItems,
    ): array {
        $items = [];

        foreach ($cartItems as $item) {
            $items[] = [
                'product_id' => (int) $item->product_id,
                'price' => (float) $item->product->price,
                'quantity' => (int) $item->quantity,
            ];
        }

        return [
            'draft_id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'address_id' => $addressId,
            'note' => $note,
            'invoice' => $invoiceAttributes,
            'summary' => $summary,
            'items' => $items,
        ];
    }

    public function store(string $token, array $draft): void
    {
        Cache::put($this->draftKey($token), $draft, $this->ttl());
    }

    public function get(string $token): ?array
    {
        $draft = Cache::get($this->draftKey($token));

        return is_array($draft) ? $draft : null;
    }

    public function forget(string $token): void
    {
        Cache::forget($this->draftKey($token));
        Cache::forget($this->completedKey($token));
    }

    public function markCompleted(string $token, string $orderCode): void
    {
        Cache::put($this->completedKey($token), $orderCode, $this->ttl());
        Cache::forget($this->draftKey($token));
    }

    public function completedOrderCode(string $token): ?string
    {
        $code = Cache::get($this->completedKey($token));

        return is_string($code) ? $code : null;
    }

    private function draftKey(string $token): string
    {
        return 'iyzico_checkout_draft:'.$token;
    }

    private function completedKey(string $token): string
    {
        return 'iyzico_checkout_completed:'.$token;
    }

    private function ttl(): \DateTimeInterface
    {
        return now()->addMinutes((int) config('iyzico.draft_ttl_minutes', 120));
    }
}
