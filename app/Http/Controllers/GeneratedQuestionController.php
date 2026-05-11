<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\GeneratedQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeneratedQuestionController extends Controller
{
    public function generate(Request $request, Concept $concept)
    {
        abort_unless($concept->domain->user_id === auth()->id(), 403);

        $apiKey = config('services.groq.api_key');

        if (!$apiKey) {
            return back()->with('error', 'Clé API non configurée');
        }

        $type = $request->input('type', 'open');
        $prompt = $type === 'qcm'
            ? $this->buildQcmPrompt($concept)
            : $this->buildOpenPrompt($concept);

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'temperature' => 0.7,
                    'max_tokens' => 1500,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es un expert en recrutement technique. Réponds UNIQUEMENT en JSON valide, sans texte avant ou après, sans markdown, sans backticks.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Groq API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'concept' => $concept->id,
                ]);
                return back()->with('error', 'L\'API Groq ne répond pas. Réessaie dans quelques secondes.');
            }

            $content = $response->json('choices.0.message.content');
            $questions = json_decode($content, true);

            if (!$questions || !is_array($questions)) {
                throw new \Exception('Réponse JSON invalide ou vide');
            }

            if (count($questions) === 0) {
                return back()->with('error', 'Aucune question générée. Réessaie.');
            }

            GeneratedQuestion::create([
                'concept_id' => $concept->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'questions' => $questions,
            ]);

            return back()->with('success', 'Questions générées avec succès !');

        } catch (\Exception $e) {
            Log::error('Groq response parse error', [
                'message' => $e->getMessage(),
                'content' => $response->json('choices.0.message.content') ?? 'null',
            ]);
            return back()->with('error', 'Réponse invalide reçue de l\'API. Réessaie.');
        }
    }

    public function verify(Request $request, GeneratedQuestion $question)
    {
        abort_unless($question->concept->domain->user_id === auth()->id(), 403);

        $answers = $request->input('answers', []);
        $correctAnswers = $request->input('correct_answers', []);
        
        $score = 0;
        $total = count($correctAnswers);
        
        foreach ($answers as $index => $answer) {
            if (isset($correctAnswers[$index]) && $answer == $correctAnswers[$index]) {
                $score++;
            }
        }
        
        $percentage = $total > 0 ? round(($score / $total) * 100) : 0;
        
        return back()->with('qcm_result', [
            'score' => $score,
            'total' => $total,
            'percentage' => $percentage,
            'question_id' => $question->id,
            'answers' => $answers,
        ]);
    }

    public function destroy(GeneratedQuestion $question)
    {
        abort_unless($question->concept->domain->user_id === auth()->id(), 403);

        $question->delete();

        return back()->with('success', 'Génération supprimée.');
    }

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