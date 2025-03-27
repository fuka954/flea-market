<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Profile;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        $addressData = $addressRequest->validated();
        $profileData = $profileRequest->validated();

        $mergedData = array_merge($addressData, $profileData);
        $user = Auth::user();
        $mergedData['user_id'] =  $user->id;
        $profile = $user->profile;

        if (!$profile) {
            $profile = Profile::create($mergedData);

            if ($profileRequest->hasFile('image')) {
                $file = $profileRequest->file('image');
                $path = $file->store('images/profile', 'public');
                $profile->image = $path;
                $profile->save();
            }
        } else {
            $profile->fill($mergedData);

            if ($profileRequest->hasFile('image')) {
                $file = $profileRequest->file('image');
                $path = $file->store('images/profile', 'public');

                if ($profile->image) {
                    Storage::disk('public')->delete($profile->image);
                }

                $profile->image = $path;
            }

            $profile->save();
        }

        return redirect('/mypage');
    }

    public function edit()
    {
        $user = Auth::user();

        if ($user->profile) {
            $profile = $user->profile;
        } else {
            $profile = collect(); 
        }

        return view('edit', compact('profile'));
    }

    public function show()
    {
        $filter = ['search-text' => ''];
        $user = Auth::user();
        $profile = $user->profile;
        $sell = Product::where('sell_user', $profile->id)->get();
        $buy = Product::where('buy_user', $profile->id)->get();
        
        return view('profile', compact('sell', 'buy', 'profile'));
    }

}
