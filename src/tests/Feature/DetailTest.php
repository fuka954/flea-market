<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Profile;
use App\Models\User;
use App\Models\Condition;

class DetailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //7.商品詳細情報取得➀、➁
    public function test_product_details_display_correctly()
    {
        $product = Product::factory()
            ->withCategories()
            ->create();

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $fashionCategory = Category::factory()->create(['category' => 'ファッション']);
        $electronicsCategory = Category::factory()->create(['category' => '家電']);
        
        $product->categories()->sync([$fashionCategory->id, $electronicsCategory->id]);

        $comment = Comment::create([
            'comment' => 'This is a comment.',
            'profile_id' => $profile->id,
        ]);

        $product->comments()->attach($comment->id);
        $response = $this->get("/item/{$product->id}");

        $response->assertStatus(200)
                ->assertSee($product->name)
                ->assertSee($product->brand)
                ->assertSee(number_format($product->price))
                ->assertSee($product->description)
                ->assertSee(Condition::find($product->condition_id)->condition)
                ->assertSee('ファッション')
                ->assertSee('家電')
                ->assertSee($comment->comment)
                ->assertSee($profile->name);
    }

    //8.いいね機能➀
    public function test_favorite_button_adds_product_to_favorites()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        
        $initialFavoriteCount = $product->favoritedByProfiles()->count();
        $this->actingAs($user);

        $response = $this->get("/item/{$product->id}");
        $response = $this->post("/item/{$product->id}/favorite");

        $response->assertRedirect("/item/{$product->id}");

        $product->refresh();

        $this->assertEquals($initialFavoriteCount + 1, $product->favoritedByProfiles()->count());
    }

    //8.いいね機能➁
    public function test_favorite_button_registers_product_as_favorite()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get("/item/{$product->id}");
        $response->assertSee('<form action="/item/' . $product->id . '/favorite" method="POST">', false);
        
        $response = $this->post("/item/{$product->id}/favorite");
        $response->assertRedirect("/item/{$product->id}");
        $response = $this->get("/item/{$product->id}");

        $response->assertSeeInOrder([
            '<form action="/item/' . $product->id . '/favorite" method="POST">',
            'class="favorite-button liked"',
        ], false);
    }

    //8.いいね機能➂
    public function test_favorite_button_removes_product_from_favorites()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        
        $initialFavoriteCount = $product->favoritedByProfiles()->count();

        $this->actingAs($user);

        $response = $this->get("/item/{$product->id}");
        $response = $this->post("/item/{$product->id}/favorite");

        $response->assertRedirect("/item/{$product->id}");

        $response = $this->post("/item/{$product->id}/favorite");

        $product->refresh();

        $this->assertEquals($initialFavoriteCount, $product->favoritedByProfiles()->count());
    }

    //9.コメント送信機能➀
    public function test_logged_in_user_can_post_comment()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->post("/item/{$product->id}/comment", [
            'comment' => 'This is a comment.',
        ]);

        $response = $this->get("/item/{$product->id}");
        $response->assertSee('This is a comment.');
    }

    //9.コメント送信機能➁
    public function test_guest_user_cannot_post_comment()
    {
        $product = Product::factory()->create();
        $response = $this->post("/item/{$product->id}/comment", [
            'comment' => 'This is a comment.',
        ]);

        $response->assertSessionHasErrors('comment');
    }

    //9.コメント送信機能➂
    public function test_comment_is_required()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/item/{$product->id}/comment", [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors('comment');
    }

    //9.コメント送信機能➃
    public function test_comment_maximum_length()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user);

        $longComment = str_repeat('a', 256);

        $response = $this->post("/item/{$product->id}/comment", [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors('comment');
    }
}
