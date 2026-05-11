<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="breadcrumb mb-2">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <span class="breadcrumb-separator">/</span>
                    <a href="{{ route('domains.index') }}">Domaines</a>
                    <span class="breadcrumb-separator">/</span>
                    <a href="{{ route('domains.concepts.index', $domain) }}">{{ $domain->name }}</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="text-primary">Modifier</span>
                </div>
                <h2 class="font-display text-2xl font-semibold text-primary">Modifier le Concept</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-static p-6">
                <form method="POST" action="{{ route('domains.concepts.update', [$domain, $concept]) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="title" class="form-label">Titre</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $concept->title) }}"
                            class="form-input"
                            placeholder="Ex: Eloquent N+1 Problem"
                            required
                        />
                        @error('title')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="explanation" class="form-label">Explication</label>
                        <textarea
                            id="explanation"
                            name="explanation"
                            rows="8"
                            class="form-textarea"
                            placeholder="Explique ce concept dans tes propres mots..."
                            required
                        >{{ old('explanation', $concept->explanation) }}</textarea>
                        @error('explanation')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="difficulty" class="form-label">Difficulté</label>
                            <select id="difficulty" name="difficulty" class="form-select" required>
                                <option value="junior" {{ $concept->difficulty === 'junior' ? 'selected' : '' }}>Junior</option>
                                <option value="mid" {{ $concept->difficulty === 'mid' ? 'selected' : '' }}>Mid</option>
                                <option value="senior" {{ $concept->difficulty === 'senior' ? 'selected' : '' }}>Senior</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status" class="form-label">Statut</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="a_revoir" {{ $concept->status === 'a_revoir' ? 'selected' : '' }}>À revoir</option>
                                <option value="en_cours" {{ $concept->status === 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="maitrise" {{ $concept->status === 'maitrise' ? 'selected' : '' }}>Maîtrisé</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-default">
                        <form action="{{ route('domains.concepts.destroy', [$domain, $concept]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce concept ?')">
                                Supprimer
                            </button>
                        </form>

                        <div class="flex gap-3">
                            <a href="{{ route('domains.concepts.show', [$domain, $concept]) }}" class="btn btn-ghost">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>