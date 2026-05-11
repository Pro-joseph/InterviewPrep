<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\GeneratedQuestion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GeneratedQuestionFactory extends Factory
{
    protected $model = GeneratedQuestion::class;

    public function definition(): array
    {
        return [
            'concept_id' => Concept::factory(),
            'user_id' => User::factory(),
            'questions' => [
                ['question' => fake()->sentence() . '?'],
                ['question' => fake()->sentence() . '?'],
                ['question' => fake()->sentence() . '?'],
                ['question' => fake()->sentence() . '?'],
                ['question' => fake()->sentence() . '?'],
            ],
        ];
    }
}