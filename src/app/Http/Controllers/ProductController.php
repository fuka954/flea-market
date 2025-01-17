<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $profile = $user->profile;

            if ($profile) {
                $myList = $profile->favorites;
            } else {
                $myList = collect();
            }
        } else {
            $myList = collect();
        }
        
        $productList = Product::Paginate(8);

        return view('index', compact('productList'), compact('myList'));
    }
}
