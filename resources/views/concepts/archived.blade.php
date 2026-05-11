<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-primary">Concepts Archivés</h2>
                <p class="text-sm text-secondary mt-1">Restaure ou supprime définitivement les concepts archivés</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($archivedConcepts->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h3 class="empty-state-title">Aucun concept archivé</h3>
                    <p class="empty-state-text">Les concepts supprimés apparaîtront ici</p>
                    <a href="{{ route('domains.index') }}" class="btn btn-primary">
                        Retour aux domaines
                    </a>
                </div>
            @else
                <div class="card-static overflow-hidden">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Concept</th>
                                <th>Domaine</th>
                                <th>Archivé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedConcepts as $concept)
                                <tr>
                                    <td>
                                        <div>
                                            <p class="font-medium text-primary">{{ $concept->title }}</p>
                                            <p class="text-sm text-muted mt-1">{{ Str::limit($concept->explanation, 80) }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full" style="background: {{ $concept->domain->color }};"></span>
                                            <span class="text-secondary">{{ $concept->domain->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary">{{ $concept->deleted_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('concepts.restore', $concept) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary text-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    Restaurer
                                                </button>
                                            </form>
                                            <form action="{{ route('concepts.force-delete', $concept) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger text-sm" onclick="return confirm('Êtes-vous sûr ? Cette action est irréversible.')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $archivedConcepts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>