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
                    <span class="text-primary">Nouveau</span>
                </div>
                <h2 class="font-display text-2xl font-semibold text-primary">Nouveau Concept</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-static p-6">
                <form method="POST" action="{{ route('domains.concepts.store', $domain) }}">
                    @csrf

                    <div class="form-group">
                        <label for="title" class="form-label">Titre</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
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
                        >{{ old('explanation') }}</textarea>
                        @error('explanation')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="difficulty" class="form-label">Difficulté</label>
                            <select id="difficulty" name="difficulty" class="form-select" required>
                                <option value="junior" {{ old('difficulty') === 'junior' ? 'selected' : '' }}>Junior</option>
                                <option value="mid" {{ old('difficulty') === 'mid' ? 'selected' : '' }}>Mid</option>
                                <option value="senior" {{ old('difficulty') === 'senior' ? 'selected' : '' }}>Senior</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status" class="form-label">Statut</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="a_revoir" {{ old('status') === 'a_revoir' ? 'selected' : '' }}>À revoir</option>
                                <option value="en_cours" {{ old('status') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="maitrise" {{ old('status') === 'maitrise' ? 'selected' : '' }}>Maîtrisé</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('domains.concepts.index', $domain) }}" class="btn btn-ghost">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>