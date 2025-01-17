<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;

class ProfileController extends Controller
{
    
    public function update(Request $request)
    {
        $profile = $request->only(['user_id','name','post_code','address','address','building']);
        Profile::create($profile);
        return redirect('/');
    }

    public function edit()
    {
        // $userId = session('userId');
        // $user = User::findOrFail($userId);
        // session()->flash('user', $user);
        return view('profile');


    }

    // public function show($id)
    // {
    //     $user = User::findOrFail($id);
    //     return redirect('/mypage/profile', compact('user'));
    // }
}
