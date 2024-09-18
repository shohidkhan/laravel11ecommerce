<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller {
    //

    function index() {
        $user_id = Auth::id();
        $carts = Cart::where("user_id", $user_id)->with("product")->get();

        $total = 0;
        foreach ($carts as $cart) {
            $total += $cart->total;
        }
        //add 3% vat with total
        $vat = ($total * 3 / 100);
        $shipping = 20;
        $totalwithVat = $total + $vat + $shipping;
        return view("cart.index", compact("carts", "total", "vat", "totalwithVat", "shipping"));
    }
    function addToCart(Request $request) {
        $user_id = Auth::user()->id;
        $total = $request->price * $request->quantity;
        Cart::create([
            "user_id" => $user_id,
            "product_id" => $request->product_id,
            "quantity" => $request->quantity,
            "price" => $request->price,
            "total" => $total,
            "size" => $request->size,
            "color" => $request->color,
        ]);
        return redirect()->back();
    }

    function removeCart($id) {
        $user_id = Auth::id();
        Cart::where("id", $id)->where("user_id", $user_id)->delete();
        return redirect()->back()->with("status", "Cart item removed successfully");
    }

    function updateCart(Request $request) {
        // dd($request->all());
        $user_id = Auth::id();

        foreach ($request->cart_id as $key => $id) {
            $cart = Cart::findOrFail($id);
            Cart::where("id", $id)->where("user_id", $user_id)->update([
                "quantity" => $request->quantity[$key],
                "total" => $cart->price * $request->quantity[$key],
            ]);
        }

        return redirect()->back()->with("status", "Cart updated successfully");
    }
}
