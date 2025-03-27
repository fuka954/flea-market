@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
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
<div class="sell-form">
    <h2 class="sell-form__heading content__heading">商品の出品</h2>
    <div class="sell-form__inner">
        <form class="sell-form__form" action="/sell" method="post" id="sell-form" enctype="multipart/form-data">
        @csrf
            <div class="sell-form__group">
                <label class="sell-form__label" for="image">商品画像</label>
                <div class="sell-form__image-item">
                    <img class='sell-form__image' src="{{ old('image') }}" alt="プロフィール画像"  id="image-preview">
                    <input class="sell-form__file" name="image" type="file" id="file-input" value="">
                    <label class="sell-form__file-label" for="file-input" >画像を選択する</label>
                </div>
                <p class="error-message">
                    @error('image')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <h3 class="group-title">商品の詳細</h3>
            <div class="sell-form__group">
                <label class="sell-form__label">カテゴリー</label>
                <div class="content-from__checkbox-group">
                    @foreach ($categories as $category)
                        <div class="content-from__checkbox">
                            <input type="checkbox" id="{{ 'category'.$category['id'] }}" class="checkbox-input" name="category_id[]" value = "{{ $category['id'] }}" {{ in_array($category['id'], old('category_id', [])) ? 'checked' : '' }}>
                            <label for="{{ 'category'.$category['id'] }}" class="content-category">{{ $category['category'] }}</label>
                        </div>
                    @endforeach
                </div>
                <p class="error-message">
                    @error('category_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label">商品の状態</label>
                <select class='sell-form__input' name="condition_id" >
                    <option value="" selected disabled hidden>選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition['id'] }}">{{ $condition['condition'] }}</option>
                    @endforeach
                </select>
                <p class="error-message">
                    @error('condition_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <h3 class="group-title">商品名と説明</h3>
            <div class="sell-form__group">
                <label class="sell-form__label" for="name">商品名</label>
                <input class="sell-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
                <p class="error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="brand">ブランド名</label>
                <input class="sell-form__input" type="text" name="brand" id="brand" value="{{ old('brand') }}">
                <p class="error-message">
                    @error('brand')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="description">商品の説明</label>
                <textarea class="sell-form__textarea" name="description" id="description" rows="8">{{ old('description') }}</textarea>
                <p class="error-message">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="price">販売価格</label>
                <input class="sell-form__input" type="text" name="price" id="price" value="{{ old('price') }}">
                <p class="error-message">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="sell-form__btn btn" type="submit" value="出品する">
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

    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("price");

        if (input) {
            let oldValue = input.value.replace(/[^\d]/g, "");

            if (oldValue) {
                input.value = "¥" + Number(oldValue).toLocaleString();
            } else {
                input.value = "¥";
            }

            input.addEventListener("input", function () {
                let value = input.value.replace(/[^\d]/g, "");
                value = Number(value).toLocaleString();
                input.value = "¥" + value;
            });

            document.getElementById("sell-form").addEventListener("submit", function (e) {
                let submittedValue = input.value.replace(/[^\d]/g, "");
                input.value = submittedValue;
            });
        }
    });
</script>
@endsection