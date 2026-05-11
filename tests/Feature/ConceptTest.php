<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConceptTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_concept_in_their_domain(): void
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/domains/{$domain->id}/concepts", [
            'title' => 'Eloquent N+1 Problem',
            'explanation' => 'Le N+1 survient quand...',
            'difficulty' => 'junior',
            'status' => 'a_revoir',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('concepts', [
            'title' => 'Eloquent N+1 Problem',
            'domain_id' => $domain->id,
        ]);
    }

    public function test_concept_status_defaults_to_a_revoir(): void
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);
        $concept = Concept::factory()->create(['domain_id' => $domain->id]);

        $this->assertEquals('a_revoir', $concept->status);
    }

    public function test_user_can_update_concept_status_quickly(): void
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);
        $concept = Concept::factory()->create([
            'domain_id' => $domain->id,
            'user_id' => $user->id,
            'status' => 'a_revoir',
        ]);

        $response = $this->actingAs($user)
            ->patch("/concepts/{$concept->id}/status", ['status' => 'en_cours']);

        $response->assertStatus(302);
        $this->assertDatabaseHas('concepts', [
            'id' => $concept->id,
            'status' => 'en_cours',
        ]);
    }

    public function test_status_label_accessor_returns_correct_label(): void
    {
        $concept = Concept::factory()->make(['status' => 'maitrise']);
        $this->assertEquals('Maîtrisé', $concept->statusLabel);
    }

    public function test_difficulty_label_accessor_returns_correct_label(): void
    {
        $concept = Concept::factory()->make(['difficulty' => 'senior']);
        $this->assertEquals('Senior', $concept->difficultyLabel);
    }

    public function test_user_can_filter_concepts_by_status(): void
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);
        $concept1 = Concept::factory()->create(['domain_id' => $domain->id, 'user_id' => $user->id, 'status' => 'maitrise']);
        $concept2 = Concept::factory()->create(['domain_id' => $domain->id, 'user_id' => $user->id, 'status' => 'a_revoir']);

        $response = $this->actingAs($user)
            ->get("/domains/{$domain->id}/concepts?status=maitrise");

        $response->assertStatus(200);
    }
}