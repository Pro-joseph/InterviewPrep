<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_see_their_domains(): void
    {
        $user = User::factory()->create();
        $domains = Domain::factory(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get('/domains')
            ->assertOk();

        $this->assertDatabaseHas('domains', ['id' => $domains->first()->id, 'user_id' => $user->id]);
    }

    public function test_user_cannot_see_another_user_domains(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $other->id]);

        $response = $this->actingAs($user)
            ->get('/domains');

        $this->assertDatabaseMissing('domains', ['id' => $domain->id, 'user_id' => $user->id]);
    }

    public function test_user_can_create_a_domain(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/domains', [
            'name' => 'Laravel ORM',
            'color' => '#3B82F6',
        ]);

        $response->assertRedirect('/domains');

        $this->assertDatabaseHas('domains', [
            'name' => 'Laravel ORM',
            'user_id' => $user->id,
        ]);
    }

    public function test_domain_name_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/domains', ['name' => '', 'color' => '#fff'])
            ->assertSessionHasErrors('name');
    }

    public function test_user_can_update_their_domain(): void
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/domains/{$domain->id}", [
            'name' => 'PHP OOP',
            'color' => '#10B981',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('domains', ['name' => 'PHP OOP']);
    }

    public function test_user_can_delete_their_domain(): void
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete("/domains/{$domain->id}");

        $response->assertStatus(302);
        $this->assertSoftDeleted('domains', ['id' => $domain->id]);
    }

    public function test_user_cannot_delete_another_user_domain(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $other->id]);

        $response = $this->actingAs($user)
            ->delete("/domains/{$domain->id}");

        $response->assertStatus(403);
    }
}