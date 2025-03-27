<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Profile;
use App\Models\Category;
use App\Models\Condition;

class SellTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //15.出品商品情報登録➀
    public function test_product_is_sold_correctly()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $category = Category::factory()->create(['category' => 'ファッション']);
        $condition = Condition::factory()->create(['condition' => '家電']);

        $this->actingAs($user);

        Storage::fake('public');
        $image = UploadedFile::fake()->create('product.jpg', 500, 'image/jpeg');

        $data = [
            'category_id' => [$category->id],
            'condition_id' => $condition->id,
            'name' => 'スマートフォン',
            'brand' => 'ブランド名',
            'description' => '最新のスマートフォンです。',
            'price' => '50000',
            'image' => $image,
        ];

        $response = $this->post('/sell', $data);
        $response->assertStatus(302);
        $product = Product::where('name', 'スマートフォン')->first();

        $this->assertNotNull($product, '商品がデータベースに保存されていません。');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'スマートフォン',
            'brand' => 'ブランド名',
            'description' => '最新のスマートフォンです。',
            'price' => 50000,
            'sell_user' => $profile->id,
            'condition_id' => $condition->id,
        ]);

        $this->assertDatabaseHas('product_category', [
            'product_id' => $product->id,
            'category_id' => $category->id,
        ]);

        Storage::disk('public')->assertExists("images/product/{$image->hashName()}");
    }
}
