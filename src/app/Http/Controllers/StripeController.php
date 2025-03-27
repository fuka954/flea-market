<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class StripeController extends Controller
{
    public function success($itemId)
    {
        $product = Product::findOrFail($itemId);
        $profileId = Auth::user()->profile->id;

        $shippingPostCode = session('shipping_post_code');
        $shippingAddress = session('shipping_address');
        $shippingBuilding = session('shipping_building');

        $product->update([
            'sold_flag' => 1,
            'buy_user' => $profileId,
            'shipping_post_code' => $shippingPostCode,
            'shipping_address' => $shippingAddress,
            'shipping_building' => $shippingBuilding,
        ]);

        session()->forget(['shipping_post_code', 'shipping_address', 'shipping_building']);

        return redirect('/mypage')->with('active', 'buy');
    }

    public function cancel($itemId)
    {
        return redirect()->back();
    }
}
