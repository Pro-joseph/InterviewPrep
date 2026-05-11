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
        $apiKey = config('services.groq.api_key');

        if (!$apiKey) {
            return back()->with('error', 'Clé API non configurée');
        }

        $prompt = "Génère 5 questions d'entretien technique pour le concept suivant:\n\n";
        $prompt .= "Titre: {$concept->title}\n\n";
        $prompt .= "Explication: {$concept->explanation}\n\n";
        $prompt .= "Retourne uniquement un tableau JSON au format: [{\"question\": \"texte\"}, ...]";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un expert en recrutement technique. Génère des questions d\'entretien pertinentes et réalistes.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            if (!$response->successful()) {
                Log::error('Groq API error: ' . $response->body());
                return back()->with('error', 'Erreur lors de la génération des questions');
            }

            $content = $response->json('choices.0.message.content');
            $questions = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                preg_match_all('/"question":\s*"([^"]+)"/', $content, $matches);
                $questions = array_map(fn($q) => ['question' => $q], $matches[1] ?? []);
            }

            if (empty($questions)) {
                return back()->with('error', 'Aucune question générée');
            }

            GeneratedQuestion::create([
                'concept_id' => $concept->id,
                'user_id' => auth()->id(),
                'questions' => $questions,
            ]);

            return back()->with('success', 'Questions générées avec succès');

        } catch (\Exception $e) {
            Log::error('Question generation error: ' . $e->getMessage());
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(GeneratedQuestion $question)
    {
        $this->authorize('delete', $question);
        $question->delete();

        return back()->with('success', 'Questions supprimées');
    }
}