@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('search')
<form class="search-form" action="/" method="post">
@csrf
    <input class="header__search" type="search" name="search-text" placeholder="なにをお探しですか？" value="{{ $filter['search-text'] }}">
    <input type="hidden" name="tab" id="tab-input" value="{{ request('tab', 'recommend') }}">
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
    <div class="tabs">
        <span class="tab {{ $tab === 'recommend' ? 'active' : '' }}" data-target="recommend">おすすめ</span>
        <span class="tab {{ $tab === 'mylist' ? 'active' : '' }}" data-target="mylist">マイリスト</span>
    </div>
    <div class="product-container">
        <div class="content-form__product-list product-list" id="recommend">
            <div class="content-form__card-list">
                @foreach ($productList as $product)
                <a class="content-form__card" href="/item/{{ $product['id'] }}">
                    <div class="content-form__group">
                        <img class="content-from__img" src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        <span class="content-from__text">{{ $product['name'] }}</span>
                    </div>
                    @if ($product['sold_flag'] == 1)
                        <div class="sold-overlay">SOLD</div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
        <div class="content-form__product-list product-list hidden" id="mylist">
            <div class="content-form__card-list">
                @foreach ($myList as $product)
                <a class="content-form__card" href="/item/{{ $product['id'] }}">
                    <div class="content-form__group">
                        <img class="content-from__img" src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        <span class="content-from__text">{{ $product['name'] }}</span>
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
    const tabInput = document.getElementById('tab-input');

    const params = new URLSearchParams(window.location.search);
    const activeTab = params.get('tab') || tabInput.value;

    setActiveTab(activeTab);

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetId = tab.getAttribute('data-target');

            setActiveTab(targetId);
            tabInput.value = targetId;

            const newUrl = `/?tab=${targetId}`;
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