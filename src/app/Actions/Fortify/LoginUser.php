<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\AttemptToAuthenticate;

class LoginUser extends AttemptToAuthenticate
{
    public function handle(Request $request, $next)
    {
        // 親クラスの処理を呼び出す
        $user = parent::handle($request, $next);

        // メール認証が完了していない場合
        if ($user && !$user->hasVerifiedEmail()) {
            // ユーザーをログアウトして、エラーメッセージを表示
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'メール認証を完了してください。']);
        }

        // 認証完了
        return $user;
    }
}
