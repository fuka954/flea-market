@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="alert alert-danger">
            <p>
                メール認証が完了していません。<br/>
                登録時のメールアドレス宛にメールを送信しています。<br/>
                メールをご確認の上、認証を行ってください。
            </p>
        </div>
    </div>
@endsection