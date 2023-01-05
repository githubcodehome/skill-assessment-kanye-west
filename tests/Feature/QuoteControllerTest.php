<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class QuoteControllerTest extends TestCase
{

    /**
     * @covers \App\Http\Controllers\QuoteController::main
     * @group main-page
     */
    public function test_main_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('main'));
        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Profile/Quotes'));
    }

    /**
     * @covers \App\Http\Controllers\QuoteController::favorites
     * @group favorites-page
     */
    public function test_favorites_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('favorites'));
        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Profile/Favorites'));
    }
}
