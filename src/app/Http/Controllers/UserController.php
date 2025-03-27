<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class UserController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $user = $request->only(['name','email', 'password']);
        $user['password'] = Hash::make($user['password']);
        $userId = User::create($user)->id;
        session()->flash('userId', $userId);

        return redirect('/mypage/profile');
    }
}
