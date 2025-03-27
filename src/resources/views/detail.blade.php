@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
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
<div class="detail">
    <div class="detail__inner">
        <div class="detail__image">
            <div class="detail__image-group">
                <img class="detail__image-img" src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}">
                @if ($product['sold_flag'] == 1)
                    <div class="sold-overlay">SOLD</div>
                @endif
            </div>
        </div>
        <div class="detail__content">
            <div class="detail__content-group">
                <div class="purchase-form__group">
                    <h2 class="purchase-form__product-name">{{ $product['name'] }}</h2>
                    <p class="purchase-form__product-brand">{{ $product['brand'] }}</p>
                    <p class="purchase-form__product-price">
                        <span class="price-item">&yen; </span>{{ number_format($product['price']) }}<span class="price-item"> (税込)</span>
                    </p>
                    <div class="purchase-form__icons">
                        <div class="purchase-form__icons-group">
                            <form action="/item/{{ $product['id'] }}/favorite" method="POST">
                            @csrf
                                <button type="submit" class="favorite-button {{ $isFavorited ? 'liked' : '' }}">
                                    <img src="{{ asset('images/星アイコン.png') }}" alt="星アイコン" class="purchase-form__icon icon">
                                </button>
                            </form>
                            <p>{{ $favoriteCount }}</p>
                        </div>
                        
                        <div class="purchase-form__icons-group">
                            <img src="{{ asset('images/ふきだしアイコン.png')}}" alt="ふきだしアイコン" class="purchase-form__icon icon">
                            <p>{{ $commentCount }}</p>
                        </div>
                    </div>
                    <a class="purchase-form__btn btn @if($product['sold_flag'] == 1) disabled @endif" href="/purchase/{{ $product['id'] }}">購入手続きへ</a>
                </div>
            </div>
            <div class="detail__content-group">
                <h3 class="content-title">商品説明</h3>
                <p>{{ $product['description'] }}</p>
            </div>  
            <div class="detail__content-group">
                <h3 class="content-title">商品情報</h3>
                <div class= "detail__content-category">
                    <div class="content-label">
                        <label>カテゴリー</label>
                    </div>
                    <div class= "content-item">
                        <div class= "category-group">
                            @foreach ($categories as $category)
                                <label class= "content-category">{{ $category }}</label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class= "detail__content-condition">
                    <div class="content-label">
                        <label>商品状態</label>
                    </div>
                    <div class= "content-item">
                        <span class= "content-condition">{{ $condition['condition'] }}</span>
                    </div>
                </div>
            </div>

            <div class="detail__content-group">
                <h3 class="content-title">コメント(<span>{{ $commentCount }}</span>)</h3>
                <div class="comment-list">
                    @foreach ($comments as $comment)
                        <div class="comment-group">
                            <div class="comment-item">
                                <img class="profile__image" src="{{ $comment->profile->image ? asset('storage/' . $comment->profile->image) : asset('images/placeholder.png') }}" alt="プロフィール画像">
                                <p class='profile__name'>{{ $comment->profile->name }}</p>
                            </div>
                            <p class='comment'>{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>
                <form class="comment-form__form" action="/item/{{ $product['id'] }}/comment" method="POST">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $product['id'] }}">
                    <label class='comment-form__label'>商品へのコメント</label>
                    <textarea class='comment-form__textarea' name="comment" rows="8"></textarea>
                    <p class="error-message">
                        @error('comment')
                        {{ $message }}
                        @enderror
                    </p>
                    <input class="comment-form__btn btn @if($product['sold_flag'] == 1) disabled @endif" type="submit" value="コメントを投稿" @if($product['sold_flag'] == 1) disabled @endif>
                </form>
            </div>
        </div>        
    </div>
</div>
@endsection