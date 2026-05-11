<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\Domain;
use App\Models\GeneratedQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AIGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_generate_questions_for_a_concept(): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'Qu\'est-ce que le N+1 ?',
                            'Comment détecter un N+1 avec Debugbar ?',
                            'Comment résoudre un N+1 avec Eloquent ?',
                            'Qu\'est-ce que le eager loading ?',
                            'Différence entre lazy et eager loading ?',
                        ])
                    ]
                ]]
            ], 200),
        ]);

        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);
        $concept = Concept::factory()->create(['domain_id' => $domain->id, 'user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post("/concepts/{$concept->id}/generate")
            ->assertRedirect();

        $this->assertDatabaseHas('generated_questions', [
            'concept_id' => $concept->id,
        ]);
    }

    public function test_shows_error_message_when_groq_api_fails(): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([], 500),
        ]);

        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);
        $concept = Concept::factory()->create(['domain_id' => $domain->id, 'user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post("/concepts/{$concept->id}/generate")
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertNull($concept->generatedQuestions()->first());
    }

    public function test_user_can_delete_a_generation(): void
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);
        $concept = Concept::factory()->create(['domain_id' => $domain->id, 'user_id' => $user->id]);
        $gen = GeneratedQuestion::factory()->create(['concept_id' => $concept->id, 'user_id' => $user->id]);

        $this->actingAs($user)
            ->delete("/questions/{$gen->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('generated_questions', ['id' => $gen->id]);
    }
}