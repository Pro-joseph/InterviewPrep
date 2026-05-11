<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Concept;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalConcepts = Concept::where('user_id', $user->id)->count();
        $masteredConcepts = Concept::where('user_id', $user->id)->where('status', 'maitrise')->count();
        $inProgressConcepts = Concept::where('user_id', $user->id)->where('status', 'en_cours')->count();
        $toReviewConcepts = Concept::where('user_id', $user->id)->where('status', 'a_revoir')->count();

        $domains = Domain::where('user_id', $user->id)
            ->withCount('concepts')
            ->get()
            ->map(function ($domain) {
                $domain->total_concepts = $domain->concepts_count;
                $domain->mastered_concepts = $domain->concepts()->where('status', 'maitrise')->count();
                return $domain;
            });

        $concepts = Concept::where('user_id', $user->id)
            ->with('domain')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalConcepts',
            'masteredConcepts',
            'inProgressConcepts',
            'toReviewConcepts',
            'domains',
            'concepts'
        ));
    }
}