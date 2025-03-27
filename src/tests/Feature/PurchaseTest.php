<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Profile;

class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    //11.支払い方法選択機能➀
    public function test_payment_method_selection_is_reflected_immediately()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $product = Product::factory()->create();

        $this->actingAs($user);

        $response = $this->get("/purchase/{$product->id}");

        $response->assertStatus(200);

        $this->post("/purchase/{$product->id}", [
            'payment_method' => 'コンビニ払い',
        ]);

        $response->assertSee('コンビニ払い', false);
    }
}
