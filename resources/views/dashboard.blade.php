<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-primary">Dashboard</h2>
                <p class="text-sm text-secondary mt-1">Bienvenue {{ Auth::user()->name }}, voici ta progression</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('domains.create') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau Domaine
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card animate-fade-in stagger-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-3xl font-bold text-primary">{{ $totalConcepts }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-secondary">Total Concepts</h3>
                </div>

                <div class="stat-card animate-fade-in stagger-2">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-3xl font-bold text-green-500">{{ $masteredConcepts }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-secondary">Maîtrisés</h3>
                </div>

                <div class="stat-card animate-fade-in stagger-3">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-3xl font-bold text-yellow-500">{{ $inProgressConcepts }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-secondary">En cours</h3>
                </div>

                <div class="stat-card animate-fade-in stagger-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-red-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-3xl font-bold text-red-500">{{ $toReviewConcepts }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-secondary">À revoir</h3>
                </div>
            </div>

            @if($domains->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 card-static p-6">
                    <h3 class="font-display text-lg font-semibold text-primary mb-6">Progression par Domaine</h3>
                    <div class="space-y-5">
                        @foreach($domains as $domain)
                        <div class="flex items-center gap-4">
                            <div class="w-3 h-3 rounded-full" style="background: {{ $domain->color }};"></div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium">{{ $domain->name }}</span>
                                    <span class="text-sm text-muted">{{ $domain->mastered_concepts }}/{{ $domain->total_concepts }} maîtrisés</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $domain->total_concepts > 0 ? ($domain->mastered_concepts / $domain->total_concepts * 100) : 0 }}%;"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-static p-6">
                    <h3 class="font-display text-lg font-semibold text-primary mb-6">Aperçu Rapide</h3>
                    <div class="space-y-6">
                        @php
                            $bestDomain = $domains->sortByDesc(function($d) { return $d->total_concepts > 0 ? $d->mastered_concepts / $d->total_concepts : 0; })->first();
                            $worstDomain = $domains->sortBy(function($d) { return $d->total_concepts > 0 ? $d->mastered_concepts / $d->total_concepts : 1; })->first();
                        @endphp
                        @if($bestDomain && $bestDomain->total_concepts > 0)
                        <div class="flex items-start gap-4 p-4 rounded-lg bg-tertiary">
                            <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-primary">Meilleur Domaine</h4>
                                <p class="text-sm text-secondary mt-1">{{ $bestDomain->name }} - {{ round($bestDomain->mastered_concepts / $bestDomain->total_concepts * 100) }}% maîtrisé</p>
                            </div>
                        </div>
                        @endif

                        @if($worstDomain && $worstDomain->total_concepts > 0)
                        <div class="flex items-start gap-4 p-4 rounded-lg bg-tertiary">
                            <div class="w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-primary">À améliorer</h4>
                                <p class="text-sm text-secondary mt-1">{{ $worstDomain->name }} - {{ round($worstDomain->mastered_concepts / $worstDomain->total_concepts * 100) }}% maîtrisé</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-static p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-display text-lg font-semibold text-primary">Domaines</h3>
                    <a href="{{ route('domains.index') }}" class="text-sm text-primary hover:underline">Voir tout →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($domains as $domain)
                    <a href="{{ route('domains.concepts.index', $domain) }}" class="domain-card group">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-3 h-3 rounded-full" style="background: {{ $domain->color }};"></div>
                            <span class="font-semibold text-primary group-hover:text-primary">{{ $domain->name }}</span>
                        </div>
                        <div class="progress-bar mb-2">
                            <div class="progress-fill" style="width: {{ $domain->total_concepts > 0 ? ($domain->mastered_concepts / $domain->total_concepts * 100) : 0 }}%;"></div>
                        </div>
                        <p class="text-sm text-muted">{{ $domain->mastered_concepts }}/{{ $domain->total_concepts }} maîtrisés</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="empty-state-title">Aucun domaine</h3>
                <p class="empty-state-text">Commence par créer ton premier domaine technique</p>
                <a href="{{ route('domains.create') }}" class="btn btn-primary">
                    Créer un domaine
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>