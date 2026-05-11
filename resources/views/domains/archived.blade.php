<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-primary">Domaines Archivés</h2>
                <p class="text-sm text-secondary mt-1">Restaure ou supprime définitivement les domaines archivés</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($archivedDomains->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h3 class="empty-state-title">Aucun domaine archivé</h3>
                    <p class="empty-state-text">Les domaines supprimés apparaîtront ici</p>
                    <a href="{{ route('domains.index') }}" class="btn btn-primary">
                        Retour aux domaines
                    </a>
                </div>
            @else
                <div class="card-static overflow-hidden">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Domaine</th>
                                <th>Concepts</th>
                                <th>Archivé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedDomains as $domain)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <span class="w-3 h-3 rounded-full" style="background: {{ $domain->color }};"></span>
                                            <span class="font-medium text-primary">{{ $domain->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary">{{ $domain->concepts_count }} concepts</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary">{{ $domain->deleted_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('domains.restore', $domain) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary text-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    Restaurer
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
                    {{ $archivedDomains->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>