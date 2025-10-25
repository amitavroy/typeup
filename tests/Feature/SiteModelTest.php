<?php

use App\Models\Site;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('site has fillable properties', function () {
    $site = new Site;

    expect($site->getFillable())->toBe([
        'name',
        'domain',
        'site_key',
    ]);
});

test('site has correct table name', function () {
    $site = new Site;

    expect($site->getTable())->toBe('sites');
});

test('site can be created with factory', function () {
    $site = Site::factory()->create();

    expect($site)->toBeInstanceOf(Site::class)
        ->and($site->id)->not->toBeNull()
        ->and($site->name)->not->toBeNull()
        ->and($site->domain)->not->toBeNull()
        ->and($site->site_key)->not->toBeNull()
        ->and($site->created_at)->not->toBeNull()
        ->and($site->updated_at)->not->toBeNull();
});

test('site can be created with mass assignment', function () {
    $data = [
        'name' => 'Test Site',
        'domain' => 'example.com',
        'site_key' => 'test-key-123',
    ];

    $site = Site::create($data);

    expect($site->name)->toBe('Test Site')
        ->and($site->domain)->toBe('example.com')
        ->and($site->site_key)->toBe('test-key-123');
});

test('site domain must be unique', function () {
    Site::factory()->create(['domain' => 'example.com']);

    expect(fn () => Site::factory()->create(['domain' => 'example.com']))
        ->toThrow(QueryException::class);
});

test('site site_key must be unique', function () {
    Site::factory()->create(['site_key' => 'unique-key']);

    expect(fn () => Site::factory()->create(['site_key' => 'unique-key']))
        ->toThrow(QueryException::class);
});

test('site factory generates unique domains', function () {
    $site1 = Site::factory()->create();
    $site2 = Site::factory()->create();

    expect($site1->domain)->not->toBe($site2->domain);
});

test('site factory generates unique site_keys', function () {
    $site1 = Site::factory()->create();
    $site2 = Site::factory()->create();

    expect($site1->site_key)->not->toBe($site2->site_key);
});

test('site uses timestamps', function () {
    $site = Site::factory()->create();

    expect($site->created_at)->toBeInstanceOf(DateTime::class)
        ->and($site->updated_at)->toBeInstanceOf(DateTime::class);
});
