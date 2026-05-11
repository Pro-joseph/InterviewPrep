<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Concept;
use App\Http\Requests\StoreConceptRequest;
use App\Http\Requests\UpdateConceptRequest;
use Illuminate\Http\Request;

class ConceptController extends Controller
{
    public function index(Request $request, Domain $domain)
    {
        $query = $domain->concepts()->where('user_id', auth()->id());

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('difficulty') && $request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }

        $concepts = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('domains.concepts.index', compact('domain', 'concepts'));
    }

    public function create(Domain $domain)
    {
        return view('domains.concepts.create', compact('domain'));
    }

    public function store(StoreConceptRequest $request, Domain $domain)
    {
        $domain->concepts()->create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'explanation' => $request->explanation,
            'difficulty' => $request->difficulty,
            'status' => $request->status,
        ]);

        return redirect()->route('domains.concepts.index', $domain)->with('success', 'Concept créé');
    }

    public function show(Domain $domain, Concept $concept)
    {
        $concept->load('generatedQuestions');
        return view('domains.concepts.show', compact('domain', 'concept'));
    }

    public function edit(Domain $domain, Concept $concept)
    {
        return view('domains.concepts.edit', compact('domain', 'concept'));
    }

    public function update(UpdateConceptRequest $request, Domain $domain, Concept $concept)
    {
        $concept->update($request->validated());

        return redirect()->route('domains.concepts.show', [$domain, $concept])->with('success', 'Concept mis à jour');
    }

    public function destroy(Domain $domain, Concept $concept)
    {
        $concept->delete();

        return redirect()->route('domains.concepts.index', $domain)->with('success', 'Concept supprimé');
    }

    public function updateStatus(Request $request, Concept $concept)
    {
        $request->validate([
            'status' => 'required|in:a_revoir,en_cours,maitrise'
        ]);

        $concept->update(['status' => $request->status]);

        return back()->with('success', 'Statut mis à jour');
    }

    public function archived()
    {
        $archived = Concept::onlyTrashed()
            ->where('user_id', auth()->id())
            ->with('domain')
            ->orderBy('deleted_at', 'desc')
            ->paginate(12);

        return view('concepts.archived', compact('archived'));
    }

    public function restore(Concept $concept)
    {
        $concept->restore();
        return back()->with('success', 'Concept restauré');
    }

    public function forceDelete(Concept $concept)
    {
        $concept->forceDelete();
        return back()->with('success', 'Concept supprimé définitivement');
    }
}