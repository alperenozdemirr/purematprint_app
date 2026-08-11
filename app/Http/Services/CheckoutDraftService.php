<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\PaymentProvider;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CheckoutDraftService
{
    public function __construct(protected ProductPropertySelectionService $propertySelection)
    {
    }

    public function createDraft(
        User $user,
        int $addressId,
        ?string $note,
        array $invoiceAttributes,
        array $summary,
        iterable $cartItems,
        PaymentProvider $paymentProvider,
        ?string $designType = null,
    ): array {
        $items = [];

        foreach ($cartItems as $item) {
            $resolved = $this->propertySelection->resolveFromCartItem($item);

            $items[] = [
                'product_id' => (int) $item->product_id,
                'price' => (float) $resolved['unit_price'],
                'quantity' => (int) $item->quantity,
                'title' => (string) $item->product->title,
                'properties' => $resolved['lines'],
            ];
        }

        return [
            'draft_id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'address_id' => $addressId,
            'note' => $note,
            'design_type' => $designType ?? \App\Enums\OrderDesignType::default()->value,
            'source_channel' => \App\Enums\OrderSourceChannel::default()->value,
            'invoice' => $invoiceAttributes,
            'summary' => $summary,
            'items' => $items,
            'payment_provider' => $paymentProvider->value,
        ];
    }

    public function store(PaymentProvider $provider, string $reference, array $draft): void
    {
        Cache::put($this->draftKey($provider, $reference), $draft, $this->ttl());
    }

    public function get(PaymentProvider $provider, string $reference): ?array
    {
        $draft = Cache::get($this->draftKey($provider, $reference));

        return is_array($draft) ? $draft : null;
    }

    public function forget(PaymentProvider $provider, string $reference): void
    {
        $draft = $this->get($provider, $reference);

        if ($draft !== null) {
            app(CheckoutOrderFileService::class)->cleanupDraftFiles($draft);
        }

        Cache::forget($this->draftKey($provider, $reference));
        Cache::forget($this->completedKey($provider, $reference));
    }

    public function markCompleted(PaymentProvider $provider, string $reference, string $orderCode): void
    {
        Cache::put($this->completedKey($provider, $reference), $orderCode, $this->ttl());
        Cache::forget($this->draftKey($provider, $reference));
    }

    public function completedOrderCode(PaymentProvider $provider, string $reference): ?string
    {
        $code = Cache::get($this->completedKey($provider, $reference));

        return is_string($code) ? $code : null;
    }

    private function draftKey(PaymentProvider $provider, string $reference): string
    {
        return 'checkout_draft:'.$provider->value.':'.$reference;
    }

    private function completedKey(PaymentProvider $provider, string $reference): string
    {
        return 'checkout_completed:'.$provider->value.':'.$reference;
    }

    private function ttl(): \DateTimeInterface
    {
        return now()->addMinutes((int) config('checkout.draft_ttl_minutes', 120));
    }
}
