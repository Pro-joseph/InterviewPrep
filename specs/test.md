# TEST.md — InterviewPrep

## Stack de test
- **Framework** : PHPUnit (inclus dans Laravel)
- **Helpers** : `RefreshDatabase`, `WithFaker`, `actingAs()`
- **Factories** : `UserFactory`, `DomainFactory`, `ConceptFactory`, `GeneratedQuestionFactory`

---

## 1. Authentification

### US1 — Inscription
```php
// tests/Feature/Auth/RegisterTest.php

test('user can register with valid data', function () {
    $response = $this->post('/register', [
        'name'                  => 'Youssef',
        'email'                 => 'youssef@test.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', ['email' => 'youssef@test.com']);
});

test('user cannot register with duplicate email', function () {
    User::factory()->create(['email' => 'youssef@test.com']);

    $response = $this->post('/register', [
        'name'                  => 'Youssef',
        'email'                 => 'youssef@test.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
});
```

### US1 — Connexion / Déconnexion
```php
// tests/Feature/Auth/LoginTest.php

test('user can login with correct credentials', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);

    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('user cannot login with wrong password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email'    => $user->email,
        'password' => 'wrongpassword',
    ])->assertSessionHasErrors('email');
});

test('user can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout');

    $this->assertGuest();
});
```

---

## 2. Domaines

```php
// tests/Feature/DomainTest.php

test('authenticated user can see their domains', function () {
    $user    = User::factory()->create();
    $domains = Domain::factory(3)->create(['user_id' => $user->id]);

    $this->actingAs($user)
         ->get('/domains')
         ->assertOk()
         ->assertSee($domains->first()->name);
});

test('user cannot see another user domains', function () {
    $user  = User::factory()->create();
    $other = User::factory()->create();
    $domain = Domain::factory()->create(['user_id' => $other->id]);

    $this->actingAs($user)
         ->get('/domains')
         ->assertDontSee($domain->name);
});

test('user can create a domain', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/domains', [
        'name'  => 'Laravel ORM',
        'color' => '#3B82F6',
    ])->assertRedirect('/domains');

    $this->assertDatabaseHas('domains', [
        'name'    => 'Laravel ORM',
        'user_id' => $user->id,
    ]);
});

test('domain name is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
         ->post('/domains', ['name' => '', 'color' => '#fff'])
         ->assertSessionHasErrors('name');
});

test('user can update their domain', function () {
    $user   = User::factory()->create();
    $domain = Domain::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->put("/domains/{$domain->id}", [
        'name'  => 'PHP OOP',
        'color' => '#10B981',
    ])->assertRedirect('/domains');

    $this->assertDatabaseHas('domains', ['name' => 'PHP OOP']);
});

test('user can delete their domain', function () {
    $user   = User::factory()->create();
    $domain = Domain::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
         ->delete("/domains/{$domain->id}")
         ->assertRedirect('/domains');

    $this->assertDatabaseMissing('domains', ['id' => $domain->id]);
});

test('user cannot delete another user domain', function () {
    $user   = User::factory()->create();
    $other  = User::factory()->create();
    $domain = Domain::factory()->create(['user_id' => $other->id]);

    $this->actingAs($user)
         ->delete("/domains/{$domain->id}")
         ->assertForbidden();
});
```

---

## 3. Concepts

```php
// tests/Feature/ConceptTest.php

test('user can create a concept in their domain', function () {
    $user   = User::factory()->create();
    $domain = Domain::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->post("/domains/{$domain->id}/concepts", [
        'title'       => 'Eloquent N+1 Problem',
        'explanation' => 'Le N+1 survient quand...',
        'difficulty'  => 'junior',
    ])->assertRedirect();

    $this->assertDatabaseHas('concepts', [
        'title'     => 'Eloquent N+1 Problem',
        'status'    => 'a_revoir',
        'domain_id' => $domain->id,
    ]);
});

test('concept status defaults to a_revoir', function () {
    $user    = User::factory()->create();
    $domain  = Domain::factory()->create(['user_id' => $user->id]);
    $concept = Concept::factory()->create(['domain_id' => $domain->id]);

    $this->assertEquals('a_revoir', $concept->status);
});

test('user can update concept status quickly', function () {
    $user    = User::factory()->create();
    $domain  = Domain::factory()->create(['user_id' => $user->id]);
    $concept = Concept::factory()->create([
        'domain_id' => $domain->id,
        'status'    => 'a_revoir',
    ]);

    $this->actingAs($user)
         ->patch("/concepts/{$concept->id}/status", ['status' => 'en_cours'])
         ->assertRedirect();

    $this->assertDatabaseHas('concepts', [
        'id'     => $concept->id,
        'status' => 'en_cours',
    ]);
});

test('statusLabel accessor returns correct label', function () {
    $concept = Concept::factory()->make(['status' => 'maitrise']);
    $this->assertEquals('Maîtrisé', $concept->statusLabel);
});

test('difficultyLabel accessor returns correct label', function () {
    $concept = Concept::factory()->make(['difficulty' => 'senior']);
    $this->assertEquals('Senior', $concept->difficultyLabel);
});

test('user can filter concepts by status', function () {
    $user    = User::factory()->create();
    $domain  = Domain::factory()->create(['user_id' => $user->id]);
    Concept::factory()->create(['domain_id' => $domain->id, 'status' => 'maitrise']);
    Concept::factory()->create(['domain_id' => $domain->id, 'status' => 'a_revoir']);

    $this->actingAs($user)
         ->get("/domains/{$domain->id}/concepts?status=maitrise")
         ->assertOk()
         ->assertViewHas('concepts', fn($c) => $c->count() === 1);
});
```

---

## 4. Génération AI

```php
// tests/Feature/AIGenerationTest.php

test('user can generate questions for a concept', function () {
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

    $user    = User::factory()->create();
    $domain  = Domain::factory()->create(['user_id' => $user->id]);
    $concept = Concept::factory()->create(['domain_id' => $domain->id]);

    $this->actingAs($user)
         ->post("/concepts/{$concept->id}/generate")
         ->assertRedirect();

    $this->assertDatabaseHas('generated_questions', [
        'concept_id' => $concept->id,
    ]);
});

test('shows error message when Groq API fails', function () {
    Http::fake([
        'api.groq.com/*' => Http::response([], 500),
    ]);

    $user    = User::factory()->create();
    $domain  = Domain::factory()->create(['user_id' => $user->id]);
    $concept = Concept::factory()->create(['domain_id' => $domain->id]);

    $this->actingAs($user)
         ->post("/concepts/{$concept->id}/generate")
         ->assertRedirect()
         ->assertSessionHas('error');
});

test('user can delete a generation', function () {
    $user    = User::factory()->create();
    $domain  = Domain::factory()->create(['user_id' => $user->id]);
    $concept = Concept::factory()->create(['domain_id' => $domain->id]);
    $gen     = GeneratedQuestion::factory()->create(['concept_id' => $concept->id]);

    $this->actingAs($user)
         ->delete("/generated-questions/{$gen->id}")
         ->assertRedirect();

    $this->assertDatabaseMissing('generated_questions', ['id' => $gen->id]);
});
```

---

## Lancer les tests

```bash
# Tous les tests
php artisan test

# Avec coverage
php artisan test --coverage

# Un fichier spécifique
php artisan test tests/Feature/DomainTest.php

# Un test spécifique
php artisan test --filter='user can create a domain'
```

---

## Factories à créer

```bash
php artisan make:factory DomainFactory --model=Domain
php artisan make:factory ConceptFactory --model=Concept
php artisan make:factory GeneratedQuestionFactory --model=GeneratedQuestion
```