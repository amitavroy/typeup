<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClickEvent extends Model
{
    /** @use HasFactory<\Database\Factories\ClickEventFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'search_id',
        'content_id',
        'position',
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
            'position' => 'integer',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the search that owns the click event.
     */
    public function search(): BelongsTo
    {
        return $this->belongsTo(Search::class, 'search_id', 'search_id');
    }
}
