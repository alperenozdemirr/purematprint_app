<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'verification_code',
        'confirm_status',
    ];

    protected $casts = [
        'confirm_status' => 'boolean',
    ];
}
