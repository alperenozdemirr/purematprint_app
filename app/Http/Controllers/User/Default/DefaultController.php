<?php

namespace App\Http\Controllers\User\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    public function index(){
        return view('user.default.index');
    }

    public function loginPage(){
        return view('user.default.login');
    }
    public function registerPage(){
        return view('user.default.register');
    }
}
