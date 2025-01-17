@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css')}}">
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
<div class="profile-form">
    <h2 class="profile-form__heading content__heading">プロフィール設定</h2>
    <div class="profile-form__inner">
        <form class="profile-form__form" action="/mypage/profile" method="post">
        @csrf
            <input type="hidden" name="user_id" value="{{ session('userId') }}">
            <div class="profile-form__group">
                <img class='profile-form__image' src="placeholder.png" alt="プロフィール画像">
                <button>画像を選択する</button>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="name">ユーザー名</label>
                <input class="profile-form__input" type="text" name="name" id="name">
                <p class="register-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="post_code">郵便番号</label>
                <input class="profile-form__input" type="text" name="post_code" id="post_code">
                <p class="register-form__error-message">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="address">住所</label>
                <input class="profile-form__input" type="text" name="address" id="address">
                <p class="register-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                <input class="profile-form__input" type="text" name="building" id="building">
                <p>
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="profile-form__btn btn" type="submit" value="更新する">
        </form>
    </div>
</div>
@endsection('content')