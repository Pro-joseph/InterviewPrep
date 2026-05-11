<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-primary">Mes Domaines</h2>
                <p class="text-sm text-secondary mt-1">Gère tes domaines techniques</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('domains.archived') }}" class="btn btn-ghost">
                    Archivés
                </a>
                <button @click="$dispatch('open-modal', 'create-domain')" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau Domaine
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($domains->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="empty-state-title">Aucun domaine</h3>
                    <p class="empty-state-text">Commence par créer ton premier domaine technique</p>
                    <button @click="$dispatch('open-modal', 'create-domain')" class="btn btn-primary">
                        Créer un domaine
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($domains as $domain)
                        <div class="domain-card">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full" style="background: {{ $domain->color }};"></div>
                                    <h3 class="font-display text-lg font-semibold text-primary">{{ $domain->name }}</h3>
                                </div>
                                <div class="dropdown" x-data="{ open: false }">
                                    <button @click.stop="open = !open" class="btn btn-ghost btn-icon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" class="dropdown-menu" style="display: none;">
                                        <a href="{{ route('domains.edit', $domain) }}" class="dropdown-item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Modifier
                                        </a>
                                        <form action="{{ route('domains.destroy', $domain) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-red-500 w-full text-left">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('domains.concepts.index', $domain) }}" class="block">
                                <div class="mb-3">
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-secondary">Progression</span>
                                        <span class="text-muted">{{ $domain->mastered_concepts }}/{{ $domain->total_concepts }}</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $domain->total_concepts > 0 ? ($domain->mastered_concepts / $domain->total_concepts * 100) : 0 }}%;"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-muted">{{ $domain->total_concepts }} concepts</span>
                                    <span class="text-primary flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                        Voir →
                                    </span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <x-modal name="create-domain">
        <form method="POST" action="{{ route('domains.store') }}" class="p-6">
            @csrf
            <h3 class="font-display text-xl font-semibold text-primary mb-6">Nouveau Domaine</h3>

            <div class="form-group">
                <label for="name" class="form-label">Nom du domaine</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    placeholder="Ex: Laravel ORM, PHP OOP, MySQL"
                    required
                />
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Couleur</label>
                <div class="flex gap-3 flex-wrap">
                    @foreach(['#FF5722', '#777BB4', '#4479A1', '#00BCD4', '#9C27B0', '#F05032', '#2496ED', '#22C55E'] as $color)
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" />
                            <div class="w-8 h-8 rounded-full border-2 border-transparent peer-checked:border-primary transition-all" style="background: {{ $color }};"></div>
                        </label>
                    @endforeach
                </div>
                @error('color')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" x-on:click="$dispatch('close')" class="btn btn-ghost">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>