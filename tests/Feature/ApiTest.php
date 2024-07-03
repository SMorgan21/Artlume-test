<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('TestToken')->plainTextToken;
    $this->headers = ['Authorization' => 'Bearer ' . $this->token];
});

it('returns a successful response', function () {
    $response = $this->withHeaders($this->headers)->getJson('/api/products');
    $response->assertOk();
});

it('returns a JSON structure', function () {
    $response = $this->withHeaders($this->headers)->getJson('/api/products');
    $response->assertJsonStructure([
        '*' => ['name', 'description', 'price', 'stock', 'on_order', 'created_at', 'updated_at', 'deleted_at']
    ]);
});

it('returns no more than 5 products', function () {
    Product::factory()->count(6)->create();
    $response = $this->getJson('/api/products');
    $products = $response->json();
    expect(count($products))->toBeLessThanOrEqual(5);
});

it('returns a 401 status when not authenticated', function () {
    $response = $this->getJson('/api/products');
    $response->assertUnauthorized();
});

it('returns a 200 status when authenticated', function () {
    $response = $this->withHeaders($this->headers)->getJson('/api/products');
    $response->assertOk();
});

it('can register a user', function () {
    $response = $this->postJson('/api/register', [
        'name' => fake()->name,
        'email' => fake()->safeEmail,
        'password' => fake()->password(8),
    ]);
    $response->assertOk();
    $response->assertJson(['message' => 'User Created']);
});

it('can log in a user', function () {
    $response = $this->postJson('/api/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response->assertOk();
    $response->assertJsonStructure(['access_token']);
});

it('can log out a user', function () {
    $response = $this->withHeaders($this->headers)->postJson('/api/logout');
    $response->assertOk();
    $response->assertJson(['message' => 'logged out']);
});

it('returns a 401 status when logging out without authentication', function () {
    $response = $this->postJson('/api/logout');
    $response->assertUnauthorized();
});

it('returns a 401 status when logging in with invalid credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => $this->user->email,
        'password' => 'invalid-password',
    ]);
    $response->assertUnauthorized();
});

it('removes all tokens when logging out', function () {
    $this->withHeaders($this->headers)->postJson('/api/logout');
    $this->assertCount(0, $this->user->tokens);
})->group('auth');

it('deletes old tokens and creates a new token when logging in', function () {
    $this->withHeaders($this->headers)->postJson('/api/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $this->assertCount(1, $this->user->tokens);
})->group('auth');
