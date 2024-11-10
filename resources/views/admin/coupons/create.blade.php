@extends('layouts.admin')
@section('title', 'Add Coupon')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Coupon infomation</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('coupon.index') }}">
                        <div class="text-tiny">Coupons</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">New Coupon</div>
                </li>
            </ul>
        </div>
        <div class="wg-box">
            <form class="form-new-product form-style-1" method="POST" action="{{ route('coupon.store') }}">
                @csrf
                <fieldset class="name">
                    <div class="body-title">Coupon Code <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Coupon Code" name="code"
                        tabindex="0" value="{{ old('code') }}" >
                        @error('code')
                            <span class="text-danger text-center h6">{{ $message }}</span>
                        @enderror
                </fieldset>
                <fieldset class="category">
                    <div class="body-title">Coupon Type</div>
                    <div class="select flex-grow">
                        <select class="" name="type">
                            <option value="">Select</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percent</option>
                        </select>
                        @error('type')
                            <span class="text-danger text-center h6">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>
                <fieldset class="name">
                    <div class="body-title">Value <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Coupon Value" name="value"
                        tabindex="0" value="{{ old('value') }}" >
                </fieldset>
                <fieldset class="name">
                    <div class="body-title">Cart Value <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Cart Value"
                        name="cart_value" tabindex="0" value="{{ old('cart_value') }}" >
                        @error('cart_value')
                            <span class="text-danger text-center h6">{{ $message }}</span>
                        @enderror
                </fieldset>
                <fieldset class="name">
                    <div class="body-title">Expiry Date <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="date" placeholder="Expiry Date"
                        name="expiry_date" tabindex="0" value="{{ old('expiry_date') }}" >
                        @error('expiry_date')
                            <span class="text-danger text-center h6">{{ $message }}</span>
                        @enderror
                </fieldset>

                <fieldset class="category">
                    <div class="body-title">Coupon Status</div>
                    <div class="select flex-grow">
                        <select class="" name="status">
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>InActive</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        </select>
                        @error('status')
                            <span class="text-danger text-center h6">{{ $message }}</span>
                        @enderror 
                    </div>
                </fieldset>

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



