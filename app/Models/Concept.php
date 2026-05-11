<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concept extends Model
{
    use SoftDeletes;

    protected $fillable = ['domain_id', 'user_id', 'title', 'explanation', 'difficulty', 'status'];

    protected $casts = [
        'difficulty' => 'string',
        'status' => 'string',
    ];

    protected $attributes = [
        'status' => 'a_revoir',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generatedQuestions(): HasMany
    {
        return $this->hasMany(GeneratedQuestion::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'a_revoir' => 'À revoir',
            'en_cours' => 'En cours',
            'maitrise' => 'Maîtrisé',
            default => $this->status,
        };
    }

    public function getDifficultyLabelAttribute(): string
    {
        return match($this->difficulty) {
            'junior' => 'Junior',
            'mid' => 'Mid',
            'senior' => 'Senior',
            default => $this->difficulty,
        };
    }
}