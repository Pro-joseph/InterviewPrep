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
                    <span class="text-primary">Détail</span>
                </div>
                <h2 class="font-display text-2xl font-semibold text-primary">{{ $concept->title }}</h2>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('domains.concepts.edit', [$domain, $concept]) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-500/20 text-green-500 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-500/20 text-red-500 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-static p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="badge badge-{{ $concept->difficulty }}">
                                {{ match($concept->difficulty) {
                                    'junior' => 'Junior',
                                    'mid' => 'Mid',
                                    'senior' => 'Senior'
                                } }}
                            </span>
                            <span class="badge badge-{{ match($concept->status) {
                                'a_revoir' => 'review',
                                'en_cours' => 'progress',
                                'maitrise' => 'mastered'
                            } }}">
                                {{ match($concept->status) {
                                    'a_revoir' => 'À revoir',
                                    'en_cours' => 'En cours',
                                    'maitrise' => 'Maîtrisé'
                                } }}
                            </span>
                        </div>

                        <h3 class="font-display text-lg font-semibold text-primary mb-3">Explication</h3>
                        <div class="prose prose-invert max-w-none text-secondary leading-relaxed">
                            {{ $concept->explanation }}
                        </div>
                    </div>

                    <div class="card-static p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-display text-lg font-semibold text-primary">Questions d'Entretien</h3>
                            <form action="{{ route('questions.generate', $concept) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    Générer des questions
                                </button>
                            </form>
                        </div>

                        @if($concept->generatedQuestions->isEmpty())
                            <div class="text-center py-8">
                                <div class="w-16 h-16 rounded-full bg-tertiary flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-muted">Aucune question générée</p>
                                <p class="text-sm text-muted mt-1">Clique sur "Générer des questions" pour créer des questions d'entretien</p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach($concept->generatedQuestions->sortByDesc('created_at') as $generation)
                                    <div class="border border-default rounded-lg overflow-hidden">
                                        <div class="flex items-center justify-between p-4 bg-tertiary">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-primary">Génération du {{ $generation->created_at->format('d/m/Y à H:i') }}</p>
                                                    <p class="text-xs text-muted">{{ count($generation->questions ?? []) }} questions</p>
                                                </div>
                                            </div>
                                            <form action="{{ route('questions.destroy', $generation) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-icon text-muted hover:text-red-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="p-4 space-y-3">
                                            @foreach($generation->questions as $index => $question)
                                                <div class="generated-question">
                                                    <p class="text-sm text-secondary">{{ $index + 1 }}. {{ $question['question'] ?? $question }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="card-static p-6">
                        <h3 class="font-display text-lg font-semibold text-primary mb-4">Actions Rapides</h3>
                        <div class="space-y-3">
                            <form action="{{ route('concepts.status', $concept) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="review">
                                <button type="submit" class="btn btn-ghost w-full justify-start {{ $concept->status === 'a_revoir' ? 'bg-red-500/10 text-red-500' : '' }}">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    À revoir
                                </button>
                            </form>
                            <form action="{{ route('concepts.status', $concept) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="en_cours">
                                <button type="submit" class="btn btn-ghost w-full justify-start {{ $concept->status === 'en_cours' ? 'bg-yellow-500/10 text-yellow-500' : '' }}">
                                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                    En cours
                                </button>
                            </form>
                            <form action="{{ route('concepts.status', $concept) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="maitrise">
                                <button type="submit" class="btn btn-ghost w-full justify-start {{ $concept->status === 'maitrise' ? 'bg-green-500/10 text-green-500' : '' }}">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    Maîtrisé
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card-static p-6">
                        <h3 class="font-display text-lg font-semibold text-primary mb-4">Métadonnées</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted">Domaine</span>
                                <span class="text-secondary flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full" style="background: {{ $domain->color }};"></span>
                                    {{ $domain->name }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted">Créé le</span>
                                <span class="text-secondary">{{ $concept->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted">Modifié le</span>
                                <span class="text-secondary">{{ $concept->updated_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </x-app-layout>