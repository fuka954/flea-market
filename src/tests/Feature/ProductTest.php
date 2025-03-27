<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Profile;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    //4.商品一覧取得➀
    public function test_get_all_products()
    {
        $products = Product::factory()->count(3)->create();
        $response = $this->get('/');

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    //4.商品一覧取得➁
    public function test_purchased_products_show_sold_label()
    {
        $product = Product::factory()->create(['sold_flag' => 1]);
        $response = $this->get('/');
        $response->assertSee('SOLD');
    }

    //4.商品一覧取得➂
    public function test_user_does_not_see_own_products()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['sell_user' => $profile->id]);
        
        $this->actingAs($user);
        $response = $this->get('/');

        $response->assertDontSee($product->name);
    }

    //6.商品検索機能➀
    public function test_search_products_by_partial_name()
    {
        $product = Product::factory()->create(['name' => 'Test Product']);

        $response = $this->post('/', [
            'search-text' => 'Test',
            'tab' => 'recommend',
            '_token' => csrf_token(),
        ]);

        $response->assertSee('Test Product');
    }
}
