@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css')}}">
@endsection

@section('search')
<form class="search-from" action="/" method="post">
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
<div class="profile-form">
    <h2 class="profile-form__heading content__heading">プロフィール設定</h2>
    <div class="profile-form__inner">
        <form class="profile-form__form" action="/mypage/profile" method="post" enctype="multipart/form-data">
        @csrf
            <div class="profile-form__image-item">
                <img class='profile-form__image' src="{{ $profile->get('image') ? asset('storage/' . $profile['image']) : asset('images/placeholder.png') }}" alt="プロフィール画像"  id="image-preview">
                <input class="profile-form__file" name="image" type="file" id="file-input" value="">
                <label class="profile-form__file-label" for="file-input" >画像を選択する</label>
            </div>
            <p class="error-message">
                @error('image')
                {{ $message }}
                @enderror
            </p>
            <div class="profile-form__group">
                <label class="profile-form__label" for="name">ユーザー名</label>
                <input class="profile-form__input" type="text" name="name" id="name" value="{{ old('name', $profile['name'] ?? '') }}">
                <p class="error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="post_code">郵便番号</label>
                <input class="profile-form__input" type="text" name="post_code" id="post_code" value="{{ old('post_code', $profile['post_code'] ?? '') }}">
                <p class="error-message">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="address">住所</label>
                <input class="profile-form__input" type="text" name="address" id="address" value="{{ old('address', $profile['address'] ?? '') }}">
                <p class="error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                <input class="profile-form__input" type="text" name="building" id="building" value="{{ old('building', $profile['building'] ?? '') }}">
                <p class="error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="profile-form__btn btn" type="submit" value="更新する">
        </form>
    </div>
</div>

<script>
    document.getElementById('file-input').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('image-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection