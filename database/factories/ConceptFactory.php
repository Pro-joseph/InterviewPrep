<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConceptFactory extends Factory
{
    protected $model = Concept::class;

    public function definition(): array
    {
        return [
            'domain_id' => Domain::factory(),
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'explanation' => fake()->paragraphs(2, true),
            'difficulty' => fake()->randomElement(['junior', 'mid', 'senior']),
            'status' => 'a_revoir',
        ];
    }

    public function aRevoir(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'a_revoir']);
    }

    public function enCours(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'en_cours']);
    }

    public function maitrise(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'maitrise']);
    }

    public function junior(): static
    {
        return $this->state(fn (array $attributes) => ['difficulty' => 'junior']);
    }

    public function mid(): static
    {
        return $this->state(fn (array $attributes) => ['difficulty' => 'mid']);
    }

    public function senior(): static
    {
        return $this->state(fn (array $attributes) => ['difficulty' => 'senior']);
    }
}