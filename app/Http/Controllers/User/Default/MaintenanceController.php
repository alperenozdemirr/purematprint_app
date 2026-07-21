<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Default;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function __invoke(): View
    {
        return view('user.default.maintenance');
    }
}
