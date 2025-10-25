<?php

use App\Models\ClickEvent;
use App\Models\Search;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->site = Site::factory()->create([
        'server_token' => 'test-server-token-123',
    ]);
});

test('init search requires valid server token', function () {
    $response = $this->postJson('/api/track/search/init', [
        'metadata' => ['query' => 'test search'],
    ]);

    $response->assertStatus(401)
        ->assertJson(['error' => 'Missing required headers: X-Site-Id and X-Server-Token']);
});

test('init search with invalid server token fails', function () {
    $response = $this->postJson('/api/track/search/init', [
        'metadata' => ['query' => 'test search'],
    ], [
        'X-Site-Id' => $this->site->id,
        'X-Server-Token' => 'invalid-token',
    ]);

    $response->assertStatus(401)
        ->assertJson(['error' => 'Invalid site credentials']);
});

test('init search with valid credentials creates search', function () {
    $metadata = [
        'query' => 'test search',
        'user_agent' => 'Mozilla/5.0',
        'referrer' => 'https://example.com',
    ];

    $response = $this->postJson('/api/track/search/init', [
        'metadata' => $metadata,
    ], [
        'X-Site-Id' => $this->site->id,
        'X-Server-Token' => $this->site->server_token,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['search_id']);

    $searchId = $response->json('search_id');

    $this->assertDatabaseHas('searches', [
        'site_id' => $this->site->id,
        'search_id' => $searchId,
        'metadata->query' => 'test search',
    ]);
});

test('track click requires valid site key and search id', function () {
    $search = Search::factory()->create([
        'site_id' => $this->site->id,
    ]);

    $response = $this->postJson('/api/track/click', [
        'site_key' => 'invalid-site-key',
        'search_id' => $search->search_id,
        'content_id' => 'content-123',
        'position' => 1,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['site_key']);
});

test('track click with valid data creates click event', function () {
    $search = Search::factory()->create([
        'site_id' => $this->site->id,
    ]);

    $clickData = [
        'site_key' => $this->site->site_key,
        'search_id' => $search->search_id,
        'content_id' => 'content-123',
        'position' => 2,
        'metadata' => [
            'element_type' => 'card',
            'click_time' => '2024-01-01 12:00:00',
        ],
    ];

    $response = $this->postJson('/api/track/click', $clickData);

    $response->assertStatus(200)
        ->assertJsonStructure(['success', 'click_event_id']);

    $this->assertDatabaseHas('click_events', [
        'search_id' => $search->search_id,
        'content_id' => 'content-123',
        'position' => 2,
        'metadata->element_type' => 'card',
    ]);
});

test('track click with mismatched site key and search id fails', function () {
    $otherSite = Site::factory()->create();
    $search = Search::factory()->create([
        'site_id' => $otherSite->id,
    ]);

    $response = $this->postJson('/api/track/click', [
        'site_key' => $this->site->site_key,
        'search_id' => $search->search_id,
        'content_id' => 'content-123',
        'position' => 1,
    ]);

    $response->assertStatus(400)
        ->assertJson(['error' => 'Search ID does not belong to the provided site key']);
});

test('tracker script is served with correct content type', function () {
    $response = $this->get('/js/tracker.js');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'application/javascript')
        ->assertHeader('Cache-Control', 'max-age=3600, public');
});

test('tracker script contains expected functionality', function () {
    $response = $this->get('/js/tracker.js');

    $response->assertStatus(200);

    $scriptContent = $response->getContent();

    expect($scriptContent)->toContain('TypeUpTracker')
        ->toContain('initSearch')
        ->toContain('trackClick')
        ->toContain('setSearchId');
});

test('search model relationships work correctly', function () {
    $search = Search::factory()->create([
        'site_id' => $this->site->id,
    ]);

    $clickEvent = ClickEvent::factory()->create([
        'search_id' => $search->search_id,
    ]);

    expect($search->site)->toBeInstanceOf(Site::class);
    expect($search->clickEvents)->toHaveCount(1);
    expect($search->clickEvents->first())->toBeInstanceOf(ClickEvent::class);
});

test('click event model relationships work correctly', function () {
    $search = Search::factory()->create([
        'site_id' => $this->site->id,
    ]);

    $clickEvent = ClickEvent::factory()->create([
        'search_id' => $search->search_id,
    ]);

    expect($clickEvent->search)->toBeInstanceOf(Search::class);
    expect($clickEvent->search->search_id)->toBe($search->search_id);
});

test('site model relationships work correctly', function () {
    $search = Search::factory()->create([
        'site_id' => $this->site->id,
    ]);

    expect($this->site->searches)->toHaveCount(1);
    expect($this->site->searches->first())->toBeInstanceOf(Search::class);
});
