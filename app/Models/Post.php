<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property ?int $user_id
 * @property boolean $active
 *
 * @method static Post create(array $args = [])
 * @method static orderBy(...$args)
 */
class Post extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'text',
        'slug',
        'user_id',
        'active',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'id'     => 'integer',
        'title'  => 'string',
        'slug'   => 'string',
        'text'   => 'string',
        'active' => 'boolean',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
