@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css')}}">
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
<div class="content-form">
    <div class="profile-form__image-item">
        <img class='profile-form__image' src="{{ $profile['image'] ? asset('storage/' . $profile['image']) : asset('images/placeholder.png') }}" alt="プロフィール画像">
        <p class='profile-form__name'>{{ $profile['name'] }}</p>
        <a class="profile-form__file-label" href="/mypage/profile">プロフィールを編集</a>
    </div>
    <div class="tabs">
        <span class="tab {{ session('active', 'sell') === 'sell' ? 'active' : '' }}" data-target="sell">出品した商品</span>
        <span class="tab {{ session('active') === 'buy' ? 'active' : '' }}" data-target="buy">購入した商品</span>
    </div>
    <div class="product-container">
        <div class="content-form__product-list product-list" id="sell">
            <div class="content-form__card-list">
                @foreach ($sell as $product)
                <a class="content-form__card" href="/item/{{ $product['id'] }}">
                    <div class="content-form__group">
                        <img class="content-form__img" src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        <span class="content-form__text">{{ $product['name'] }}</span>
                    </div>
                    @if ($product['sold_flag'] == 1)
                        <div class="sold-overlay">SOLD</div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
        <div class="content-form__product-list product-list hidden" id="buy">
            <div class="content-form__card-list">
                @foreach ($buy as $product)
                <a class="content-form__card" href="/item/{{ $product['id'] }}">
                    <div class="content-form__group">
                        <img class="content-form__img" src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        <span class="content-form__text">{{ $product['name'] }}</span>
                    </div>
                    @if ($product['sold_flag'] == 1)
                        <div class="sold-overlay">SOLD</div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.tab');
    const productLists = document.querySelectorAll('.product-list');
    const sessionActiveTab = @json(session('active', 'sell'));
    const params = new URLSearchParams(window.location.search);
    let activeTab = params.get('tab') || sessionActiveTab;

    setActiveTab(activeTab);

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetId = tab.getAttribute('data-target');
            setActiveTab(targetId);

            const newUrl = targetId === 'sell' ? '/mypage?tab=sell' : '/mypage?tab=buy';
            history.pushState(null, '', newUrl);
        });
    });

    function setActiveTab(targetId) {
        tabs.forEach(t => t.classList.remove('active'));
        document.querySelector(`.tab[data-target="${targetId}"]`).classList.add('active');

        productLists.forEach(list => list.classList.add('hidden'));
        document.getElementById(targetId).classList.remove('hidden');
    }
});
</script>
@endsection