<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Profile;

class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //13.ユーザー情報取得➀
    public function test_user_information_is_displayed_correctly()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'image' => 'path/to/profile-image.jpg',
        ]);

        $sellProduct = Product::factory()->create(['sell_user' => $profile->id]);
        $buyProduct = Product::factory()->create(['buy_user' => $profile->id]);

        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee($profile->name);
        $response->assertSee(asset('storage/' . $profile->image));
        $response->assertSee($sellProduct->name);
        $response->assertSee($buyProduct->name);
    }

    //14.ユーザー情報変更➀
    public function test_user_information_initial_values_are_displayed_correctly()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'image' => 'path/to/profile-image.jpg',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'ビル名',
        ]);

        $this->actingAs($user);
        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee($profile->name);
        $response->assertSee($profile->post_code);
        $response->assertSee($profile->address);
        $response->assertSee($profile->building);
        $response->assertSee(asset('storage/' . $profile->image));
    }
}
