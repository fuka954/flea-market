@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('search')
<form class="search-form" action="/" method="post">
@csrf
    <input class="header__search" type="search" name="search-text" placeholder="なにをお探しですか？">
</form>
@endsection

@section('nav')
<nav class='header__nav'>
    @if(Auth::check())
    <form class='header-form__form' action="/logout" method="post">
    @csrf
        <input class="header__input" type="submit" value="ログアウト">
    </form>
    @else
    <a href="/login" class="header__link">ログイン</a>
    @endif
    <a href="/mypage" class="header__link">マイページ</a>
    <a href="/sell" class="header__link">出品</a>
</nav>
@endsection

@section('content')
<div class="purchase">
    <form class="purchase-form__form" action="/purchase/{{ $product['id'] }}" method="post">
    @csrf
        <div class="purchase__content">
            <div class="product-group">
                <img class="product__image" src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}">
                <div class="product__item">
                    <h2 class="purchase-form__product-name">{{ $product['name'] }}</h2>
                    <p class="purchase-form__product-price">
                        <span class="price-item">&yen; </span>{{ number_format($product['price']) }}
                    </p>
                </div>
            </div>
            <div class="purchase-group">
                <label class='purchase-label'>支払い方法</label>
                <div class="purchase-item">
                    <select class='purchase-select' id="options" name="payment_method" >
                        <option value="" selected disabled hidden>選択してください</option>
                        <option value="コンビニ払い">コンビニ払い</option>
                        <option value="カード払い">カード払い</option>
                    </select>
                    <p  class="error-message">
                        @error('payment_method')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
            </div>
            <div class="purchase-group">
                <div class="destination">
                    <label class='purchase-label'>配送先</label>
                    <a class='purchase-link' href="/purchase/address/{{ $product['id'] }}">変更する</a>
                </div>
                <div class="purchase-item">
                    <p>〒{{ old('shipping_post_code', session('address.post_code') ?? $profile['post_code']) }}</p>
                    <p>
                        <span>{{ old('shipping_address', session('address.address') ?? $profile['address']) }}</span> 
                        <span>{{ old('shipping_building', session('address.building') ?? $profile['building']) }}</span>
                    </p>
                    <p  class="error-message">
                        @error('shipping_post_code')
                        {{ $message }}
                        @enderror
                    </p>
                    <p  class="error-message">
                        @error('shipping_address')
                        {{ $message }}
                        @enderror
                    </p>
                </div>    
            </div>
        </div>
        <div class="purchase-form">
                <input type="hidden" name="shipping_post_code" value="{{ old('shipping_post_code', session('address.post_code') ?? $profile['post_code']) }}">
                <input type="hidden" name="shipping_address" value="{{ old('shipping_address', session('address.address') ?? $profile['address']) }}">
                <input type="hidden" name="shipping_building" value="{{ old('shipping_building', session('address.building') ?? $profile['building']) }}">
                <div class="purchase-form__group">
                    <label class="purchase-form__label" for="password-confirmation">商品代金</label>
                    <p class="purchase-form__product-price">
                        <span class="price-item">&yen; </span>{{ number_format($product['price']) }}
                    </p>
                </div>
                <div class="purchase-form__group">
                    <label class="purchase-form__label" for="password-confirmation">支払い方法</label>
                    <p id="selected-option"></p>
                </div>
                <input class="purchase-form__btn btn" type="submit" value="購入する">
        </div>  
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('options');
    const display = document.getElementById('selected-option');

    select.addEventListener('change', () => {
        const selectedValue = select.value;
        display.textContent = selectedValue || '選択内容がここに表示されます。';
    });
});
</script>
@endsection