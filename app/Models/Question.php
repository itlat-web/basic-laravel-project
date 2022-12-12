<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 * @property string $question_text
 * @property string $ip
 * @property boolean $verified
 *
 * @method static Question create(array $args = [])
 * @method static orderBy(...$args)
 * @method static where(...$args)
 */
class Question extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'email',
        'question_text',
        'ip',
        'verified'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'id'            => 'integer',
        'email'         => 'string',
        'question_text' => 'string',
        'ip'            => 'string',
        'verified'      => 'boolean'
    ];
}
