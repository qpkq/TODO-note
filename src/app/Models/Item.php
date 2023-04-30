<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static firstOrCreate(mixed $data)
 * @property mixed|null $image
 */

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'image',
        'content',
        'todo_list_id',
    ];

    /**
     * Relationships between items and lists.
     *
     * @return BelongsTo
     */
    public function list(): BelongsTo
    {
        return $this->belongsTo(TodoList::class);
    }

    /**
     * Relationships between tags and items.
     *
     * @return HasMany
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }
}
