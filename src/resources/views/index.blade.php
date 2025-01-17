@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('search')
<input class="header__search" type="search" placeholder="なにをお探しですか？">
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
        <span class="tab active" data-target="recommend">おすすめ</span>
        <span class="tab" data-target="mylist">マイリスト</span>
    </div>
    <div class="product-container">
        <div class="content-form__product-list product-list" id="recommend">
            <div class="content-form__card-list">
                @foreach ($productList as $product)
                <a href="/products/{{ $product['id'] }}">
                    <div class="content-form__group">
                        <img class="content-from__img" src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        <span class="content-from__text">{{ $product['name'] }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        <div class="content-form__mylist product-list hidden" id="mylist">
            <div class="content-form__card-list">
                @foreach ($myList as $product)
                <a href="/products/{{ $product['id'] }}">
                    <div class="content-form__group">
                        <img class="content-from__img" src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        <span class="content-from__text">{{ $product['name'] }}</span>
                    </div>
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

    // ページ読み込み時にクエリパラメータを確認
    const params = new URLSearchParams(window.location.search);
    const activeTab = params.get('tab') || 'recommend'; // デフォルトは'recommend'
    setActiveTab(activeTab);

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetId = tab.getAttribute('data-target');

            // タブを切り替え
            setActiveTab(targetId);

            // URLを更新
            const newUrl = targetId === 'recommend' ? '/' : `/?tab=${targetId}`;
            history.pushState(null, '', newUrl);
        });
    });

    function setActiveTab(targetId) {
        // 全てのタブを非アクティブ化
        tabs.forEach(t => t.classList.remove('active'));
        // 対象のタブをアクティブ化
        document.querySelector(`.tab[data-target="${targetId}"]`).classList.add('active');

        // 全てのリストを非表示
        productLists.forEach(list => list.classList.add('hidden'));
        // 対応するリストを表示
        document.getElementById(targetId).classList.remove('hidden');
    }
});
</script>
@endsection('content')