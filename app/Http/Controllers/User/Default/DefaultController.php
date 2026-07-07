<?php

namespace App\Http\Controllers\User\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    public function index(){
        return view('user.default.index');
    }
}
