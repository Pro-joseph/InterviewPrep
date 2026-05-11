<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index()
    {
        $domains = Domain::where('user_id', auth()->id())
            ->withCount('concepts')
            ->get()
            ->map(function ($domain) {
                $domain->total_concepts = $domain->concepts_count;
                $domain->mastered_concepts = $domain->concepts()->where('status', 'maitrise')->count();
                return $domain;
            });

        return view('domains.index', compact('domains'));
    }

    public function create()
    {
        return view('domains.create');
    }

    public function store(StoreDomainRequest $request)
    {
        Domain::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return redirect()->route('domains.index')->with('success', 'Domaine créé avec succès');
    }

    public function edit(Domain $domain)
    {
        $this->authorize('update', $domain);
        return view('domains.edit', compact('domain'));
    }

    public function update(UpdateDomainRequest $request, Domain $domain)
    {
        $this->authorize('update', $domain);

        $domain->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return redirect()->route('domains.index')->with('success', 'Domaine mis à jour');
    }

    public function destroy(Domain $domain)
    {
        $this->authorize('update', $domain);
        $domain->delete();

        return redirect()->route('domains.index')->with('success', 'Domaine supprimé');
    }
}