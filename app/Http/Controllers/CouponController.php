<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller {
    //

    function index() {
        $coupons = Coupon::orderBy('id', 'desc')->paginate(5);
        return view('admin.coupons.index', compact('coupons'));
    }

    function create() {
        return view('admin.coupons.create');
    }

    function store(Request $request) {
        $request->validate([
            "code" => "required|unique:coupons,code",
            "type" => "required",
            "value" => "required|numeric",
            "cart_value" => "required|numeric",
            "expiry_date" => "required|date",
            "status" => "required",
        ]);

        Coupon::create([
            "code" => $request->code,
            "type" => $request->type,
            "value" => $request->value,
            "cart_value" => $request->cart_value,
            "expiry_date" => $request->expiry_date,
            "status" => $request->status,
        ]);

        return redirect()->route('coupon.index')->with('status', 'Coupon created successfully');
    }

    function edit($id) {
        $coupon = Coupon::find($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    function update(Request $request, $id) {
        $request->validate([
            "code" => "required|unique:coupons,code," . $id,
            "type" => "required",
            "value" => "required|numeric",
            "cart_value" => "required|numeric",
            "expiry_date" => "required|date",
            "status" => "required",
        ]);

        $coupon = Coupon::find($id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->status = $request->status;
        $coupon->save();
        return redirect()->route('coupon.index')->with('status', 'Coupon updated successfully');
    }

    function destroy($id) {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('coupon.index')->with('status', 'Coupon deleted successfully');
    }
}
