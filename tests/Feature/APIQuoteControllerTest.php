<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class APIQuoteControllerTest extends TestCase
{
    /**
     * @covers \App\Http\Controllers\API\QuoteController::token
     * @group get-token
     */
    public function test_token_created()
    {
        $pass = Str::random(12);
        $user = User::factory()->create(['password' => Hash::make($pass)]);
        $response = $this->post(route('token', [
            'email' => $user->email,
            'password' => $pass,
            'device_name' => 'your device name',
        ]));
        $response->assertStatus(200);
        $result = $response->decodeResponseJson();
        $this->assertArrayHasKey('token', $result);

        $response = $this->get(route('api.user'));
        $response->assertStatus(302);

        $response = $this->withHeaders(['Authorization'=>'Bearer '. $result['token']])
            ->get(route('api.user'));
        $response->assertOk();

        $response = $this->actingAs($user)->get(route('api.user'));
        $response->assertStatus(200);
        $result = $response->decodeResponseJson();
        $this->assertEquals($user->email, $result['email']);
    }

    /**
     * @covers \App\Http\Controllers\API\QuoteController::get
     * @group api-quote-get
     */
    public function test_get_random_quotes_return_successful_response()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('api.get'));
        $result = $response->decodeResponseJson();
        $this->assertCount(5, $result['data']);
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route(
            'api.get',
            ['count' => 3]
        ));
        $result = $response->decodeResponseJson();
        $this->assertCount(3, $result['data']);
        $response->assertStatus(200);
    }

    /**
     * @covers \App\Http\Controllers\API\QuoteController::getFavorites
     * @group api-get-favorites
     */
    public function test_get_favorites()
    {
        $user = User::factory()->has(Favorite::factory()->count(10))->create();
        $response = $this->actingAs($user)->get(route('api.get_favorites'));
        $result = $response->decodeResponseJson();
        $this->assertEquals('OK', $result['status']);
        $response->assertStatus(200);

        $this->assertCount(10, $result['data']);
    }

    /**
     * @covers \App\Http\Controllers\API\QuoteController::addFavorite
     * @group api-add-favorite
     */
    public function test_add_favorite()
    {
        $user = User::factory()->create();
        $text = fake()->text;
        $response = $this->actingAs($user)->post(route('api.add_favorite', ['text' => $text]));
        $result = $response->decodeResponseJson();
        $this->assertEquals('OK', $result['status']);
        $response->assertStatus(200);

        $this->assertDatabaseHas('favorites', [
            'text' => $text,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @covers \App\Http\Controllers\API\QuoteController::deleteFavorite
     * @group api-delete-favorite
     */
    public function test_delete_favorite()
    {
        $user = User::factory()
            ->has(Favorite::factory())
            ->create();
        $favorite = Favorite::where('user_id', $user->id)->first();

        $response = $this->actingAs($user)->delete(route('api.delete_favorite', $favorite->id));
        $result = $response->decodeResponseJson();
        $this->assertEquals('OK', $result['status']);
        $response->assertStatus(200);

        $this->assertSoftDeleted($favorite);
    }
}
