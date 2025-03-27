<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Category;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ExhibitionRequest;

class ProductController extends Controller
{
    public function index()
    {
        $filter = ['search-text' => ''];
        $tab = 'recommend';

        if (Auth::check()) {
            $user = Auth::user();
            $profile = $user->profile;

            $myList = $profile->favorites;
            $productList = Product::where('sell_user', '<>', $profile['id'])->get();
        } else {
            $myList = collect();
            $productList = Product::all();
        }

        return view('index', compact('productList', 'myList', 'filter', 'tab'));
    }

    public function search(Request $request)
    {
        $filter = $request->only(['search-text']);
        $tab = $request->input('tab', 'recommend');
        
        if (Auth::check()) {
            $user = Auth::user();
            $profile = $user->profile;
            $tab = $request->input('tab', 'recommend');
            
            $myList = $profile->favorites()->where('name', 'like', '%' . $filter['search-text'] . '%')->get();
            $productList = Product::where('sell_user', '<>', $profile['user_id'])->search($filter)->get();
        } else {
            $myList = collect();
            $productList = Product::search($filter)->get();
        }

        return view('index', compact('productList', 'myList', 'filter', 'tab'));
    }

    public function show($itemId)
    {
        $product = Product::with(['categories', 'comments', 'favoritedByProfiles'])->withCount('favoritedByProfiles')->findOrFail($itemId);

        $condition = Condition::find($product->condition_id);
        $categories = $product->categories->pluck('category')->toArray();
        $comments = $product->comments()->get();
        $commentCount = $product->comments->count();

        $isFavorited = Auth::check() && $product->favoritedByProfiles->contains(Auth::user()->profile);

        $favoriteCount = $product->favorited_by_profiles_count;

        return view('detail', compact('product', 'categories', 'condition', 'comments', 'commentCount', 'isFavorited', 'favoriteCount'));
    }

    public function storeBuy(PurchaseRequest $request, $itemId)
    {
        $product = Product::findOrFail($itemId);
        $profileId = Auth::user()->profile->id;

        Stripe::setApiKey(env('STRIPE_SECRET'));

        session([
            'shipping_post_code' => $request->input('shipping_post_code'),
            'shipping_address' => $request->input('shipping_address'),
            'shipping_building' => $request->input('shipping_building'),
        ]);

        $paymentMethod = $request->input('payment_method');

        $paymentMethodTypes = ['card'];

        if ($paymentMethod === 'コンビニ払い') {
            $paymentMethodTypes = ['konbini'];
        }
        
        $session = Session::create([
            'payment_method_types' => $paymentMethodTypes,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $product->name],
                    'unit_amount' => $product->price, 
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['itemId' => $itemId]),
            'cancel_url' => route('stripe.cancel', ['itemId' => $itemId]),
        ]);

        return redirect($session->url);
    }

    public function createBuy(Request $request, $itemId)
    {
        $user = Auth::user();
        $profile = $user->profile->first();
        $product = Product::findOrFail($itemId);

        return view('purchase',compact('product','profile'));
    }

    public function storeSell(ExhibitionRequest $request)
    {
        $profileId = Auth::user()->profile->id;

        $file = $request->file('image');
        $path = $file->store('images/product', 'public');
        
        $productData = $request->only(['name','brand','description','condition_id','price']);
        $productData['image'] =  $path;
        $productData['sell_user'] =  $profileId;
        $productData['sold_flag'] =  0;

        $product = Product::create($productData);

        $product->categories()->attach($request->category_id);

        return redirect('/mypage');
    }

    public function createSell(Request $request)
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('sell',compact('categories','conditions'));
    }

    public function edit($itemId)
    {
        $user = Auth::user();
        $profile = $user->profile->first();
        $product = Product::findOrFail($itemId);
        $address = collect();

        return view('address',compact('product','profile','address'));
    }

    public function update(AddressRequest $request, $itemId)
    {
        $address = $request->only(['post_code', 'address', 'building']);
        $user = Auth::user();
        $profile = $user->profile->first();
        $product = Product::findOrFail($itemId);

        return redirect('/purchase/' . $itemId)->with(compact('product', 'profile', 'address'));
    }

    public function favorite(Request $request, $itemId) 
    {
        if (!auth()->check()) {
            return redirect()->back();
        }

        $profile = Auth::user()->profile;
        $product = Product::findOrFail($itemId);
        $isFavorited = $product->favoritedByProfiles()->where('profile_id', $profile->id)->exists();

        if ($isFavorited) {
            $product->favoritedByProfiles()->detach($profile->id);
        } else {
            $product->favoritedByProfiles()->attach($profile->id);
        }

        return redirect()->back();
    }

    public function comment(CommentRequest $request ,$itemId)
    {
        if (!auth()->check()) {
            return redirect()->back()->withErrors(['comment' => 'ログインしていないユーザーはコメントを投稿できません']);
        }

        $profile = Auth::user()->profile;
        $comment = $request->only(['comment']);
        $comment['profile_id'] = $profile->id;
        $comment = Comment::create($comment);

        $product = Product::findOrFail($itemId);
        $product->comments()->attach($comment->id);

        return redirect()->back();
    }
}
