<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $totalwithVat = $this->calculateCartTotalWithVat();
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

    function clearCart() {
        $user_id = Auth::id();
        Cart::where("user_id", $user_id)->delete();
        return redirect()->back()->with("status", "Cart cleared successfully");
    }

    public function applyCoupon(Request $request) {
        $couponCode = $request->coupon_code;

        if (!$couponCode) {
            return redirect()->back()->with("error", "Please enter a coupon code");
        }

        $coupon = Coupon::where("code", $couponCode)->first();
        if (!$coupon) {
            return redirect()->back()->with("error", "Coupon code does not exist");
        }

        if ($coupon->expiry_date < Carbon::today()->format('Y-m-d')) {
            return redirect()->back()->with("error", "Coupon code invalid");
        }

        if ($coupon->status != 1) {
            return redirect()->back()->with("error", "Coupon code is Invalid");
        }

        $totalWithVat = $this->calculateCartTotalWithVat();
        if ($coupon->cart_value > $totalWithVat) {
            return redirect()->back()->with("error", "Coupon code is applicable only if the cart value exceeds $" . $coupon->cart_value);
        }

        // Store coupon in session
        Session::put('coupon', [
            "code" => $coupon->code,
            "type" => $coupon->type,
            "value" => $coupon->value,
            "cart_value" => $coupon->cart_value,
        ]);

        $this->calculateDiscount($totalWithVat);
        return redirect()->back()->with("success", "Coupon applied successfully");
    }

    private function calculateCartTotalWithVat() {
        $userId = Auth::id();
        $carts = Cart::where("user_id", $userId)->with("product")->get();

        $cartTotal = $carts->sum('total');
        $vat = $cartTotal * 0.03;
        $shipping = 20;

        return $cartTotal + $vat + $shipping;
    }

    private function calculateDiscount($totalWithVat) {
        if (!Session::has('coupon')) {
            return;
        }

        $coupon = Session::get("coupon");
        $discount = 0;

        if ($coupon["type"] == "fixed") {
            $discount = $coupon["value"];
        } else {
            // Percentage-based discount
            $discount = $totalWithVat * $coupon["value"] / 100;
        }

        $totalAfterDiscount = $totalWithVat - $discount;

        Session::put('discount', [
            "discount" => number_format($discount, 2, '.', ''),
            "total" => number_format($totalAfterDiscount, 2, '.', ''),
        ]);
    }

    public function removeCoupon() {
        Session::forget('coupon');
        Session::forget('discount');
        return redirect()->back()->with("status", "Coupon removed successfully");
    }

}
