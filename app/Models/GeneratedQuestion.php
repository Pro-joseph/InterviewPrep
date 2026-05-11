<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedQuestion extends Model
{
    protected $fillable = ['concept_id', 'user_id', 'type', 'questions'];

    protected $casts = [
        'questions' => 'array',
        'type' => 'string',
    ];

    protected $attributes = [
        'type' => 'open',
    ];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}