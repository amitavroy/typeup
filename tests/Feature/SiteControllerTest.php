<?php

use App\Models\Site;
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

it('can update a site with valid data', function () {
    $user = User::factory()->create();
    $site = Site::factory()->create();

    $this->actingAs($user);

    $response = $this->put("/sites/{$site->id}", [
        'name' => 'Updated Site Name',
        'domain' => 'https://updated-example.com',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('sites', [
        'id' => $site->id,
        'name' => 'Updated Site Name',
        'domain' => 'https://updated-example.com',
    ]);
});

it('validates required fields when updating a site', function () {
    $user = User::factory()->create();
    $site = Site::factory()->create();

    $this->actingAs($user);

    $response = $this->put("/sites/{$site->id}", []);

    $response->assertSessionHasErrors(['name', 'domain']);
});

it('validates domain format when updating a site', function () {
    $user = User::factory()->create();
    $site = Site::factory()->create();

    $this->actingAs($user);

    $response = $this->put("/sites/{$site->id}", [
        'name' => 'Updated Site Name',
        'domain' => 'invalid-domain',
    ]);

    $response->assertSessionHasErrors(['domain']);
});

it('allows updating site with same domain', function () {
    $user = User::factory()->create();
    $site = Site::factory()->create();
    $originalDomain = $site->domain;

    $this->actingAs($user);

    $response = $this->put("/sites/{$site->id}", [
        'name' => 'Updated Site Name',
        'domain' => $originalDomain, // Same domain should be allowed
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('sites', [
        'id' => $site->id,
        'name' => 'Updated Site Name',
        'domain' => $originalDomain,
    ]);
});

it('prevents updating site with domain that belongs to another site', function () {
    $user = User::factory()->create();
    $site1 = Site::factory()->create();
    $site2 = Site::factory()->create();

    $this->actingAs($user);

    $response = $this->put("/sites/{$site1->id}", [
        'name' => 'Updated Site Name',
        'domain' => $site2->domain, // Domain from another site should fail
    ]);

    $response->assertSessionHasErrors(['domain']);
});

it('requires authentication to update a site', function () {
    $site = Site::factory()->create();

    $response = $this->put("/sites/{$site->id}", [
        'name' => 'Updated Site Name',
        'domain' => 'https://updated-example.com',
    ]);

    $response->assertRedirect('/login');
});
