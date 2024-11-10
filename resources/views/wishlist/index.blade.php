@extends('layouts.app')
@section('title', 'Shop Page')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Wishlist</h2>
     
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
                <th>Product Stock</th>
                <th>Add To cart</th>
                <th></th>
              </tr>
            </thead>
            @auth
            <tbody>
              
                @forelse ( $wishlists as $wishlist )
                <tr>
                    <td>
                      <div class="shopping-cart__product-item">
                        <img loading="lazy" src="{{ $wishlist->product->image }}" width="120" height="120" alt="{{ $wishlist->product->name }}" />
                      </div>
                    </td>
                    <td>
                      <div class="shopping-cart__product-item__detail">
                        <h4>{{ $wishlist->product->name }}</h4>
                      </div>
                    </td>
                    <td>
                      <span class="shopping-cart__product-price">$
                        
                        @if($wishlist->product->sale_price)
                            {{ $wishlist->product->sale_price }}
                        @else
                            {{ $wishlist->product->regular_price }}
                        @endif
                    </span>
                    </td>
                    <td>
                        @if($wishlist->product->quantity > 0)
                        <strong style="color:green">Product is available</strong>
                        @else
                        <strong style="color:red">Product is Out of Stock</strong>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('shop.product.details', $wishlist->product->slug) }}" class="btn btn-primary btn-addtocart">Add To Cart</a>
                    </td>
                    <td>
                      <a href="{{ route('remove-wishlist', $wishlist->id) }}" class="remove-cart">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                          <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                          <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                        </svg>
                      </a>
                    </td>
                  </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No item in Wishlist</td>
                    </tr>
                @endforelse
            </tbody>
            @else
            <div class="alert alert-danger">Please login to view your Wishlist 
                <strong><a href="{{ route('login') }}">Login</a></strong>
            </div>
            @endauth
            
          </table>
          <div class="cart-table-footer">
            <form action="{{ route('clear.whishlist') }}" method="POST">
              @csrf
              @method('DELETE')
              <button class="btn btn-light" type="submit">Clear Wishlist</button>
            </form>
          </div>
        </div>
        {{-- <div class="shopping-cart__totals-wrapper">
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
        </div> --}}
      </div>
    </section>
  </main>
@endsection