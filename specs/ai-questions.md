# AI Question Generation — Specification complète

## Vue d'ensemble

Génération de questions d'entretien via l'API Groq basée sur le titre et l'explication d'un concept.
Deux modes disponibles : **questions ouvertes** et **QCM interactif** (3 réponses, 1 correcte).

---

## Fonctionnalités

### 1. Générer des questions
- Deux boutons sur la page détail concept :
  - "Générer questions ouvertes" → 5 questions texte libre
  - "Générer QCM" → 5 questions avec 3 réponses chacune
- Appel API Groq avec titre + explication + niveau du concept
- Résultat sauvegardé en base avant affichage
- Affichage immédiat sur la page détail

### 2. Historique des générations
- Toutes les générations affichées sur la page détail concept
- Chaque génération affiche :
  - Badge type (QCM / Questions ouvertes)
  - Date/heure de génération (diffForHumans)
  - Les 5 questions avec leur format respectif
- Triées par date décroissante (newest first)

### 3. Mode QCM interactif
- L'utilisateur clique sur une réponse
- Si correcte → fond vert, bordure verte
- Si incorrecte → fond rouge, bordure rouge + la bonne réponse s'affiche en vert
- Tous les boutons désactivés après un choix (pas de deuxième tentative)

### 4. Supprimer une génération
- Bouton supprimer sur chaque génération
- Suppression définitive (pas d'archive)

---

## Base de données

### Migration — Nouvelle colonne `type`

```bash
php artisan make:migration add_type_to_generated_questions_table
```

```php
Schema::table('generated_questions', function (Blueprint $table) {
    $table->enum('type', ['open', 'qcm'])->default('open')->after('concept_id');
});
```

### Schéma final `generated_questions`

```
generated_questions
  - id         (bigint, PK)
  - concept_id (bigint, FK → concepts, CASCADE DELETE)
  - type        (enum: open | qcm, default: open)
  - questions  (json)
  - created_at (timestamp)
  - updated_at (timestamp)
```

### Format JSON — Questions ouvertes

```json
[
  { "question": "Question 1 ?" },
  { "question": "Question 2 ?" },
  { "question": "Question 3 ?" },
  { "question": "Question 4 ?" },
  { "question": "Question 5 ?" }
]
```

### Format JSON — QCM

```json
[
  {
    "question": "Qu'est-ce que le problème N+1 en Eloquent ?",
    "answers": [
      { "text": "Une requête SQL qui retourne N+1 lignes", "correct": false },
      { "text": "N requêtes générées au lieu d'une seule avec jointure", "correct": true },
      { "text": "Un bug qui plante l'application après N requêtes", "correct": false }
    ]
  }
]
```

### Model `GeneratedQuestion`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedQuestion extends Model
{
    protected $fillable = ['concept_id', 'type', 'questions'];

    protected $casts = [
        'questions' => 'array',
    ];

    public function concept()
    {
        return $this->belongsTo(Concept::class);
    }
}
```

---

## Intégration API Groq

### Configuration `.env`

```env
GROQ_API_KEY=gsk_xxxxxxxxxxxxxxxxxxxx
```

### Endpoint & modèle

| Paramètre | Valeur |
|-----------|--------|
| Endpoint  | `https://api.groq.com/openai/v1/chat/completions` |
| Model     | `llama-3.1-8b-instant` |
| Temperature | `0.7` |
| Max tokens | `1500` |
| Timeout   | `60` secondes |

---

## Controller — `GeneratedQuestionController`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\GeneratedQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeneratedQuestionController extends Controller
{
    public function generate(Concept $concept, Request $request)
    {
        // Autorisation
        abort_unless($concept->domain->user_id === auth()->id(), 403);

        $type   = $request->input('type', 'open');
        $prompt = $type === 'qcm'
            ? $this->buildQcmPrompt($concept)
            : $this->buildOpenPrompt($concept);

        // Appel API Groq via Http:: facade (zéro package externe)
        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type'  => 'application/json',
            ])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => 'llama-3.1-8b-instant',
                'temperature' => 0.7,
                'max_tokens'  => 1500,
                'messages'    => [
                    [
                        'role'    => 'system',
                        'content' => 'Tu es un expert en recrutement technique. Réponds UNIQUEMENT en JSON valide, sans texte avant ou après, sans markdown, sans backticks.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

        // Gestion erreur API
        if ($response->failed()) {
            logger()->error('Groq API error', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'concept' => $concept->id,
            ]);
            return back()->with('error', 'L\'API Groq ne répond pas. Réessaie dans quelques secondes.');
        }

        // Parsing de la réponse
        try {
            $content   = $response->json('choices.0.message.content');
            $questions = json_decode($content, true);

            if (!$questions || !is_array($questions)) {
                throw new \Exception('Réponse JSON invalide ou vide');
            }

            if (count($questions) === 0) {
                return back()->with('error', 'Aucune question générée. Réessaie.');
            }

            // Sauvegarde en base AVANT affichage
            GeneratedQuestion::create([
                'concept_id' => $concept->id,
                'type'       => $type,
                'questions'  => $questions,
            ]);

            return back()->with('success', 'Questions générées avec succès !');

        } catch (\Exception $e) {
            logger()->error('Groq response parse error', [
                'message' => $e->getMessage(),
                'content' => $response->json('choices.0.message.content') ?? 'null',
            ]);
            return back()->with('error', 'Réponse invalide reçue de l\'API. Réessaie.');
        }
    }

    public function destroy(GeneratedQuestion $question)
    {
        abort_unless($question->concept->domain->user_id === auth()->id(), 403);

        $question->delete();

        return back()->with('success', 'Génération supprimée.');
    }

    // -------------------------------------------------------------------------

    private function buildOpenPrompt(Concept $concept): string
    {
        return <<<PROMPT
Génère exactement 5 questions d'entretien techniques sur le concept suivant.

Titre : {$concept->title}
Explication : {$concept->explanation}
Niveau : {$concept->difficultyLabel}

Réponds UNIQUEMENT avec un tableau JSON valide sans aucun texte autour :
[
  { "question": "Question 1 ?" },
  { "question": "Question 2 ?" },
  { "question": "Question 3 ?" },
  { "question": "Question 4 ?" },
  { "question": "Question 5 ?" }
]
PROMPT;
    }

    private function buildQcmPrompt(Concept $concept): string
    {
        return <<<PROMPT
Génère exactement 5 questions QCM sur le concept suivant.

Titre : {$concept->title}
Explication : {$concept->explanation}
Niveau : {$concept->difficultyLabel}

Réponds UNIQUEMENT avec un tableau JSON valide, sans markdown, sans texte autour.
Format strict à respecter :
[
  {
    "question": "La question ici ?",
    "answers": [
      { "text": "Réponse A", "correct": false },
      { "text": "Réponse B", "correct": true },
      { "text": "Réponse C", "correct": false }
    ]
  }
]

Règles obligatoires :
- Exactement 3 réponses par question
- Exactement 1 seule réponse correcte par question (correct: true)
- Les réponses doivent être plausibles et proches pour que ce soit difficile
- L'ordre des réponses doit être aléatoire (la correcte pas toujours en 2ème position)
- Pas de "Toutes les réponses" ou "Aucune des réponses" comme option
PROMPT;
    }
}
```

---

## Routes — `web.php`

```php
Route::middleware('auth')->group(function () {
    // Génération (open ou qcm selon le champ type en POST)
    Route::post('/concepts/{concept}/generate', [GeneratedQuestionController::class, 'generate'])
         ->name('questions.generate');

    // Suppression
    Route::delete('/questions/{question}', [GeneratedQuestionController::class, 'destroy'])
         ->name('questions.destroy');
});
```

---

## Vues Blade

### Boutons de génération — sur `concepts/show.blade.php`

```html
<div class="flex gap-3 mt-6">

    {{-- Questions ouvertes --}}
    <form method="POST" action="{{ route('questions.generate', $concept) }}">
        @csrf
        <input type="hidden" name="type" value="open">
        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700
                       text-white text-sm font-medium rounded-lg transition">
            🎯 Générer questions ouvertes
        </button>
    </form>

    {{-- QCM --}}
    <form method="POST" action="{{ route('questions.generate', $concept) }}">
        @csrf
        <input type="hidden" name="type" value="qcm">
        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700
                       text-white text-sm font-medium rounded-lg transition">
            📝 Générer QCM
        </button>
    </form>

</div>
```

### Historique des générations — partial `_generations.blade.php`

```html
@forelse($concept->generatedQuestions()->latest()->get() as $gen)

<div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">

    {{-- Header de la génération --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full
                {{ $gen->type === 'qcm'
                    ? 'bg-purple-100 text-purple-800'
                    : 'bg-indigo-100 text-indigo-800' }}">
                {{ $gen->type === 'qcm' ? '📝 QCM' : '🎯 Questions ouvertes' }}
            </span>
            <span class="text-xs text-gray-400">
                {{ $gen->created_at->diffForHumans() }}
            </span>
        </div>
        <form method="POST" action="{{ route('questions.destroy', $gen) }}">
            @csrf @method('DELETE')
            <button class="text-xs text-gray-400 hover:text-red-500 transition">
                Supprimer
            </button>
        </form>
    </div>

    {{-- Affichage selon le type --}}
    @if($gen->type === 'qcm')

        {{-- MODE QCM INTERACTIF --}}
        @foreach($gen->questions as $i => $item)
        <div class="mb-6 qcm-group" data-group="{{ $gen->id }}-{{ $i }}">
            <p class="text-sm font-medium text-gray-900 mb-3">
                {{ $i + 1 }}. {{ $item['question'] }}
            </p>
            <div class="space-y-2">
                @foreach($item['answers'] as $j => $answer)
                <button
                    type="button"
                    onclick="revealAnswer(this)"
                    data-correct="{{ $answer['correct'] ? 'true' : 'false' }}"
                    data-group="{{ $gen->id }}-{{ $i }}"
                    class="answer-btn w-full text-left text-sm px-4 py-2.5 rounded-lg
                           border border-gray-200 bg-gray-50 hover:bg-gray-100
                           transition-all duration-200 cursor-pointer">
                    <span class="font-medium mr-2">{{ chr(65 + $j) }}.</span>
                    {{ $answer['text'] }}
                </button>
                @endforeach
            </div>
        </div>
        @endforeach

    @else

        {{-- MODE QUESTIONS OUVERTES --}}
        <ol class="space-y-2 list-decimal list-inside">
            @foreach($gen->questions as $item)
            <li class="text-sm text-gray-700 py-2 border-b border-gray-100 last:border-0">
                {{ $item['question'] }}
            </li>
            @endforeach
        </ol>

    @endif

</div>

@empty
<div class="text-center py-10 text-gray-400 text-sm">
    Aucune génération pour l'instant. Clique sur un bouton ci-dessus.
</div>
@endforelse
```

### Script JS — Logique QCM

```html
<script>
function revealAnswer(btn) {
    const groupId  = btn.dataset.group;
    const isCorrect = btn.dataset.correct === 'true';

    // Récupère tous les boutons du même groupe question
    const allBtns = document.querySelectorAll(`[data-group="${groupId}"]`);

    // Désactive tous les boutons du groupe
    allBtns.forEach(b => {
        b.disabled = true;
        b.classList.remove('hover:bg-gray-100', 'bg-gray-50', 'cursor-pointer');
        b.classList.add('cursor-default');
    });

    // Colore le bouton cliqué
    if (isCorrect) {
        btn.classList.add('bg-green-100', 'border-green-400', 'text-green-800', 'font-medium');
    } else {
        btn.classList.add('bg-red-100', 'border-red-400', 'text-red-700');

        // Révèle la bonne réponse
        allBtns.forEach(b => {
            if (b.dataset.correct === 'true') {
                b.classList.add('bg-green-100', 'border-green-400', 'text-green-800', 'font-medium');
            }
        });
    }
}
</script>
```

---

## Gestion des erreurs

| Situation | Comportement |
|-----------|-------------|
| Clé API absente | Message : "Clé API Groq manquante dans .env" |
| API timeout (>60s) | Message : "L'API Groq ne répond pas. Réessaie." |
| Erreur 4xx / 5xx | Message : "Erreur API" + log dans `storage/logs/laravel.log` |
| JSON invalide reçu | Message : "Réponse invalide reçue de l'API. Réessaie." |
| Tableau vide reçu | Message : "Aucune question générée. Réessaie." |

---

## Commandes à exécuter

```bash
# 1. Créer la migration pour ajouter la colonne type
php artisan make:migration add_type_to_generated_questions_table

# 2. Lancer la migration
php artisan migrate

# 3. Vérifier l'état
php artisan migrate:status
```

---

## Critères d'acceptation

### Questions ouvertes
- [x] L'utilisateur peut générer 5 questions ouvertes pour un concept
- [x] Les questions sont sauvegardées en base avant affichage
- [x] L'utilisateur peut voir l'historique de toutes les générations
- [x] L'utilisateur peut supprimer une génération
- [x] Les erreurs API sont gérées proprement (pas de page blanche)

### QCM
- [x] L'utilisateur peut générer 5 QCM avec 3 réponses chacun
- [x] Les QCM sont sauvegardés en base avec `type = 'qcm'`
- [x] Cliquer sur une réponse correcte → fond vert
- [x] Cliquer sur une réponse incorrecte → fond rouge + bonne réponse en vert
- [x] Tous les boutons désactivés après un choix
- [x] Badge distinct "QCM" vs "Questions ouvertes" dans l'historique

---

## Ce que l'agent AI a généré vs modifié manuellement

> Section pour le fichier specs/ — workflow AI-assisted

| Élément | Généré par l'agent | Modifié manuellement |
|---------|-------------------|----------------------|
| Structure Controller | ✅ Oui | Ajout du `logger()->error()` et du check `is_array()` |
| Prompt QCM | ✅ Oui | Ajout des règles "pas Toutes/Aucune" + ordre aléatoire |
| Script JS revealAnswer | ✅ Oui | Ajout `cursor-default` + gestion `data-group` par génération |
| Format JSON QCM | ✅ Oui | RAS |
| Migration `type` column | ✅ Oui | RAS |
| Vue Blade QCM | ✅ Oui | Séparation en partial `_generations.blade.php` |
