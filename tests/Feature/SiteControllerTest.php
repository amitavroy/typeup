<?php

use App\Models\User;

it('can create a site with valid data', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/sites', [
        'name' => 'Test Site',
        'domain' => 'https://example.com',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('sites', [
        'name' => 'Test Site',
        'domain' => 'https://example.com',
    ]);
});

it('validates required fields when creating a site', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/sites', []);

    $response->assertSessionHasErrors(['name', 'domain']);
});

it('validates domain format when creating a site', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/sites', [
        'name' => 'Test Site',
        'domain' => 'invalid-domain',
    ]);

    $response->assertSessionHasErrors(['domain']);
});

it('requires authentication to create a site', function () {
    $response = $this->post('/sites', [
        'name' => 'Test Site',
        'domain' => 'https://example.com',
    ]);

    $response->assertRedirect('/login');
});
