<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderActorType;
use App\Enums\OrderDesignRequestType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDesignRequest extends Model
{
    protected $fillable = [
        'order_id',
        'file_id',
        'type',
        'actor_type',
        'actor_id',
        'note',
    ];

    protected $casts = [
        'type' => OrderDesignRequestType::class,
        'actor_type' => OrderActorType::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
