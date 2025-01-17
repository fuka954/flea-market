<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // ユーザーがログインしているか確認
        if (Auth::check()) {
            // ユーザーのプロフィール情報を取得
            $user = Auth::user();

            // プロフィール情報が登録されていない場合、プロフィール編集画面にリダイレクト
            if (!$user->profile) { // `profile`はユーザーに関連するプロフィール情報が格納されているカラム
                Session::flash('userId', $user->id);
                return redirect('/mypage/profile');
            }
        }
        return $next($request);
    }
}
