<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(2, true),
            'color' => fake()->randomElement([
                '#6366F1', '#10B981', '#F59E0B', '#EF4444', 
                '#8B5CF6', '#EC4899', '#14B8A6', '#F97316'
            ]),
        ];
    }
}