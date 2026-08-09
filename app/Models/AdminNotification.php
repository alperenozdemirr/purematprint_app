<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AdminNotificationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'title',
        'body',
        'order_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'type' => AdminNotificationType::class,
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    public function url(): ?string
    {
        $code = $this->order?->code ?? ($this->data['order_code'] ?? null);

        if (! filled($code)) {
            return null;
        }

        return route('admin.orderDetailPage', $code);
    }
}
