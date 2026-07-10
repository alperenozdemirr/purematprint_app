<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Account;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('user.default.account');
    }
}
