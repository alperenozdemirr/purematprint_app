<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
    ];

    protected $casts = [
        'number' => 'integer',
    ];

    public static function ordered(): Builder
    {
        return static::query()
            ->orderBy('number')
            ->orderBy('id');
    }
}
