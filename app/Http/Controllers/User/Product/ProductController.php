<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        return view('user.shops');
    }

    public function show($slug){

        return view('user.shop-detail');
    }

    public function collectionList(){
        return view('user.collection-list');
    }
}
