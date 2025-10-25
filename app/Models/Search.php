<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Search extends Model
{
    /** @use HasFactory<\Database\Factories\SearchFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'site_id',
        'search_id',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'search_id' => 'string',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the site that owns the search.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the click events for the search.
     */
    public function clickEvents(): HasMany
    {
        return $this->hasMany(ClickEvent::class, 'search_id', 'search_id');
    }
}
