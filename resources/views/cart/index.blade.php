@extends('layouts.app')
@section('title', 'Shop Page')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Cart</h2>
      <div class="checkout-steps">
        <a href="cart.html" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Shopping Bag</span>
            <em>Manage Your Items List</em>
          </span>
        </a>
        <a href="checkout.html" class="checkout-steps__item">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Shipping and Checkout</span>
            <em>Checkout Your Items List</em>
          </span>
        </a>
        <a href="order-confirmation.html" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Confirmation</span>
            <em>Review And Submit Your Order</em>
          </span>
        </a>
      </div>
      <div class="shopping-cart">
        <div class="cart-table__wrapper">
          @if(Session::has('status'))
          <div class="alert alert-success">
            {{ Session::get('status') }}
          </div>
          @endif
          <table class="cart-table">
            <thead>
              <tr>
                <th>Product</th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th></th>
              </tr>
            </thead>
            @auth
            <tbody>
              <form action="{{ route('update-cart') }}" method="POST">
                @csrf
                @method('PUT')
                @forelse ( $carts as $cart)
                <tr>
                    <td>
                      <div class="shopping-cart__product-item">
                        <img loading="lazy" src="{{ $cart->product->image }}" width="120" height="120" alt="{{ $cart->product->name }}" />
                      </div>
                    </td>
                    <td>
                      <div class="shopping-cart__product-item__detail">
                        <h4>{{ $cart->product->name }}</h4>
                        <ul class="shopping-cart__product-item__options">
                            @if($cart->color)
                            <li>Color: {{ $cart->color }}</li>
                            @endif
                            @if($cart->color)
                            <li>Size: {{ $cart->size }}</li>
                            @endif
                        </ul>
                      </div>
                    </td>
                    <td>
                      <span class="shopping-cart__product-price">${{ $cart->price }}</span>
                    </td>
                    <td>
                      <div class="qty-control position-relative">
                        <input type="number" name="quantity[]" value="{{ $cart->quantity }}" min="1" class="qty-control__number text-center">
                        <input type="hidden" name="cart_id[]" value="{{ $cart->id }}">
                        <div class="qty-control__reduce">-</div>
                        
                        <div class="qty-control__increase">+</div>
                      </div>
                    </td>
                    <td>
                      <span class="shopping-cart__subtotal">${{ $cart->total }}</span>
                    </td>
                    <td>
                      <a href="{{ route('remove-cart', $cart->id) }}" class="remove-cart">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                          <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                          <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                        </svg>
                      </a>
                    </td>
                  </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No item in cart</td>
                    </tr>
                @endforelse
              </tbody>
            @else
            <div class="alert alert-danger">Please login to view your cart 
                <strong><a href="{{ route('login') }}">Login</a></strong>
            </div>
            @endauth
            
          </table>
          <div class="cart-table-footer">
            <button class="btn btn-light" type="submit">UPDATE CART</button>
          </form>
            <form action="#" class="position-relative bg-body">
              <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code">
              <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                value="APPLY COUPON">
            </form>
          </div>
        </div>
        <div class="shopping-cart__totals-wrapper">
          <div class="sticky-content">
            <div class="shopping-cart__totals">
              <h3>Cart Totals</h3>
              <table class="cart-totals">
                <tbody>
                  <tr>
                    <th>Subtotal</th>
                    <td>${{ $total }}</td>
                  </tr>
                  <tr>
                    <th>Shipping Charge</th>
                    <td>
                      <div class="form-check">
                        
                        <label class="form-check-label" for="free_shipping">  ${{ $shipping }}</label>
                      </div>
                      
                      
                      <div>Shipping to ALL.</div>
                      
                    </td>
                  </tr>
                  <tr>
                    <th>VAT</th>
                    <td>${{ $vat }}</td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td>${{ $totalwithVat }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="mobile_fixed-btn_wrapper">
              <div class="button-wrapper container">
                <a href="checkout.html" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection