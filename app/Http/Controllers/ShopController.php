<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller {
    //
    public function index(Request $request) {
        $size = $request->query('size');
        $order_column = "";
        $o_order = "";
        $order = $request->query('order') ? $request->query('order') : -1;
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');
        $min_price = $request->query('min') ? $request->query('min') : 1;
        $max_price = $request->query('max') ? $request->query('max') : 4000;
        switch ($order) {
        case 1;
            $order_column = "created_at";
            $o_order = "desc";
            break;
        case 2;
            $order_column = "created_at";
            $o_order = "asc";
            break;
        case 3;
            $order_column = "regular_price";
            $o_order = "asc";
            break;
        case 4;
            $order_column = "regular_price";
            $o_order = "desc";
            break;
        default:
            $order_column = "id";
            $o_order = "desc";
            break;
        }

        $brands = Brand::orderBy('name', 'asc')->with('products')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        // $products = Product::where("status", 1)
        //     ->where(function ($query) use ($f_brands) {
        //         if ($f_brands) {
        //             $query->whereIn('brand_id', explode(',', $f_brands))->orWhereRaw("'{$f_brands}' = ''");
        //         }
        //     })
        //     ->where(function ($query) use ($f_categories) {
        //         if ($f_categories) {
        //             $query->whereIn("category_id", explode(",", $f_categories))->orWhereRaw("'{$f_categories}'=''");
        //         }
        //     })
        //     ->where(function ($query) use ($min_price, $max_price) {
        //         $query->whereBetween("regular_price", [$min_price, $max_price])->orWhereBetween("sale_price", [$min_price, $max_price]);
        //     })
        //     ->orderBy($order_column, $o_order)->with('category', 'brand')->paginate($size);
        $products = Product::query()
            ->where('status', 1)
            ->when($f_brands, function ($query) use ($f_brands) {
                $brandsArray = explode(',', $f_brands);
                $query->where(function ($query) use ($brandsArray) {
                    $query->whereIn('brand_id', $brandsArray)
                        ->orWhereRaw('? = ""', [$brandsArray]);
                });
            })
            ->when($f_categories, function ($query) use ($f_categories) {
                $categoriesArray = explode(',', $f_categories);
                $query->where(function ($query) use ($categoriesArray) {
                    $query->whereIn('category_id', $categoriesArray)
                        ->orWhereRaw('? = ""', [$categoriesArray]);
                });
            })
            ->when(isset($min_price, $max_price), function ($query) use ($min_price, $max_price) {
                $query->where(function ($query) use ($min_price, $max_price) {
                    $query->whereBetween('regular_price', [$min_price, $max_price])
                        ->orWhereBetween('sale_price', [$min_price, $max_price]);
                });
            })
            ->orderBy($order_column, $o_order)
            ->with(['category', 'brand'])
            ->paginate($size);

        //most minimum price from all products
        $minimumPrice = Product::min('regular_price');
        $maximumPrice = Product::max('regular_price');

        return view('shop.index', compact('products', "size", "order", "brands", 'f_brands', 'categories', 'f_categories', 'min_price', 'max_price', 'minimumPrice', 'maximumPrice'));
    }

    public function product_details($slug) {
        $productDetails = Product::where("slug", $slug)->with('category', 'brand')->first();
        $relatedPeoducts = Product::whereNot("slug", $slug)->where("category_id", $productDetails->category_id)->with('category', 'brand')->take(6)->get();
        return view("shop.product-details", compact('productDetails', 'relatedPeoducts'));
    }
}
