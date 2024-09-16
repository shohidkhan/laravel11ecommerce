<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller {
    //
    public function index() {
        $products = Product::where("status", 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('shop.index', compact('products'));
    }

    public function product_details($slug) {
        $productDetails = Product::where("slug", $slug)->with('category', 'brand')->first();
        $relatedPeoducts = Product::whereNot("slug", $slug)->where("category_id", $productDetails->category_id)->with('category', 'brand')->take(6)->get();
        return view("shop.product-details", compact('productDetails', 'relatedPeoducts'));
    }
}
