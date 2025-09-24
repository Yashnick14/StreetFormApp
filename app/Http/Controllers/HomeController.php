<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(4)->get(); // Example: latest 8 products
        return view('home.home', compact('products'));
    }

    public function men()
    {
        $products = Product::where('category_id', 1)->get(); // 1 = Men
        return view('customers.men', compact('products'));
    }

    public function women()
    {
        $products = Product::where('category_id', 2)->get(); // 2 = Women
        return view('customers.women', compact('products'));
    }
}
