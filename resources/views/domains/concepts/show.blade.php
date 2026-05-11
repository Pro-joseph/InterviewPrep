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
            @if(session('qcm_result'))
                @php $result = session('qcm_result'); @endphp
                <div class="mb-4 p-4 rounded-lg {{ $result['percentage'] >= 60 ? 'bg-green-500/20 text-green-500' : 'bg-yellow-500/20 text-yellow-500' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold">Résultats du QCM</p>
                            <p class="text-sm">{{ $result['score'] }} / {{ $result['total'] }} réponses correctes ({{ $result['percentage'] }}%)</p>
                        </div>
                        <div class="text-2xl font-bold">
                            {{ $result['percentage'] >= 60 ? '✓' : '✗' }}
                        </div>
                    </div>
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
                            <div class="flex gap-3">
                                <form action="{{ route('questions.generate', $concept) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="open">
                                    <button type="submit" class="btn btn-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                        Questions ouvertes
                                    </button>
                                </form>
                                <form action="{{ route('questions.generate', $concept) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="qcm">
                                    <button type="submit" class="btn btn-secondary" style="background: #8B5CF6; border-color: #8B5CF6; color: white;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        QCM
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($concept->generatedQuestions->isEmpty())
                            <div class="text-center py-8">
                                <div class="w-16 h-16 rounded-full bg-tertiary flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-muted">Aucune question générée</p>
                                <p class="text-sm text-muted mt-1">Clique sur un bouton ci-dessus pour générer des questions</p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @forelse($concept->generatedQuestions()->latest()->get() as $gen)
                                    <div class="border border-default rounded-xl p-5">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $gen->type === 'qcm' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300' }}">
                                                    {{ $gen->type === 'qcm' ? 'QCM' : 'Questions ouvertes' }}
                                                </span>
                                                <span class="text-xs text-muted">
                                                    {{ $gen->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <form action="{{ route('questions.destroy', $gen) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-muted hover:text-red-500 transition">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>

                                        @if($gen->type === 'qcm')
                                            @php
                                                $showResults = session('qcm_result') && session('qcm_result')['question_id'] == $gen->id;
                                                $userAnswers = session('qcm_result')['answers'] ?? [];
                                            @endphp
                                                    @if($showResults)
                                                        <div class="mb-4 p-4 rounded-lg {{ session('qcm_result')['percentage'] >= 60 ? 'bg-green-500/20 text-green-500' : 'bg-yellow-500/20 text-yellow-500' }}">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <p class="font-semibold">Résultats du QCM</p>
                                                                    <p class="text-sm">{{ session('qcm_result')['score'] }} / {{ session('qcm_result')['total'] }} réponses correctes ({{ session('qcm_result')['percentage'] }}%)</p>
                                                                </div>
                                                                <div class="text-2xl font-bold">
                                                                    {{ session('qcm_result')['percentage'] >= 60 ? '✓' : '✗' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <form method="POST" action="{{ route('questions.verify', $gen) }}" class="qcm-form" data-gen-id="{{ $gen->id }}">
                                                        @csrf
                                                        @foreach($gen->questions as $i => $item)
                                                            @php
                                                                $correctAnswerIndex = collect($item['answers'])->search(fn($a) => $a['correct'] === true);
                                                                $userAnswer = $userAnswers[$i] ?? null;
                                                            @endphp
                                                            <div class="mb-6 qcm-question" data-group="{{ $gen->id }}-{{ $i }}">
                                                                <p class="text-sm font-medium text-primary dark:text-white mb-3">
                                                                    {{ $i + 1 }}. {{ $item['question'] }}
                                                                    @if($showResults)
                                                                        @if(isset($userAnswers[$i]) && $userAnswers[$i] == $correctAnswerIndex)
                                                                            <span class="ml-2 text-green-500 text-xs">✓ Correct</span>
                                                                        @elseif(isset($userAnswers[$i]))
                                                                            <span class="ml-2 text-red-500 text-xs">✗ Incorrect</span>
                                                                        @endif
                                                                    @endif
                                                                </p>
                                                                <div class="space-y-2">
                                                                    @foreach($item['answers'] as $j => $answer)
                                                                        @php
                                                                            $isCorrect = $j === $correctAnswerIndex;
                                                                            $isUserAnswer = isset($userAnswers[$i]) && $userAnswers[$i] == $j;
                                                                            $classes = 'answer-option flex items-center gap-3 w-full text-left text-sm px-4 py-2.5 rounded-lg border transition-all duration-200';
                                                                            
                                                                            if ($showResults) {
                                                                                if ($isCorrect) {
                                                                                    $classes .= ' bg-green-100 border-green-400 dark:bg-green-900/30 dark:border-green-600';
                                                                                } elseif ($isUserAnswer && !$isCorrect) {
                                                                                    $classes .= ' bg-red-100 border-red-400 dark:bg-red-900/30 dark:border-red-600';
                                                                                } else {
                                                                                    $classes .= ' bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 opacity-50';
                                                                                }
                                                                            } else {
                                                                                $classes .= ' border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer';
                                                                            }
                                                                        @endphp
                                                                        <label class="{{ $classes }}">
                                                                            @if(!$showResults)
                                                                                <input type="radio" name="answers[{{ $i }}]" value="{{ $j }}" class="w-4 h-4 text-indigo-600" required>
                                                                            @endif
                                                                            <span class="font-medium">{{ chr(65 + $j) }}.</span>
                                                                            <span class="answer-text">{{ $answer['text'] }}</span>
                                                                            @if($showResults && $isCorrect)
                                                                                <span class="ml-auto text-green-600 text-xs">Correct</span>
                                                                            @endif
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                                <input type="hidden" name="correct_answers[]" value="{{ $correctAnswerIndex }}">
                                                            </div>
                                                        @endforeach
                                                        @if(!$showResults)
                                                            <button type="submit" class="btn btn-primary w-full mt-4">
                                                                Soumettre mes réponses
                                                            </button>
                                                        @else
                                                            <a href="{{ route('domains.concepts.show', [$domain, $concept]) }}" class="btn btn-secondary w-full mt-4 inline-block text-center">
                                                                Réessayer
                                                            </a>
                                                        @endif
                                                    </form>
                                                @else
                                            <ol class="space-y-2 list-decimal list-inside">
                                                @foreach($gen->questions as $item)
                                                <li class="text-sm text-secondary dark:text-gray-300 py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                                    {{ $item['question'] }}
                                                </li>
                                                @endforeach
                                            </ol>
                                        @endif
                                    </div>
                                @empty
                                @endforelse
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

    <script>
    function revealAnswer(btn) {
        const groupId = btn.dataset.group;
        const isCorrect = btn.dataset.correct === 'true';

        const allBtns = document.querySelectorAll(`[data-group="${groupId}"]`);

        allBtns.forEach(b => {
            b.disabled = true;
            b.classList.remove('hover:bg-gray-100', 'bg-gray-50', 'cursor-pointer');
            b.classList.add('cursor-default');
        });

        if (isCorrect) {
            btn.classList.add('bg-green-100', 'border-green-400', 'text-green-800', 'font-medium', 'dark:bg-green-900/50', 'dark:border-green-600', 'dark:text-green-300');
        } else {
            btn.classList.add('bg-red-100', 'border-red-400', 'text-red-700', 'dark:bg-red-900/50', 'dark:border-red-600', 'dark:text-red-300');

            allBtns.forEach(b => {
                if (b.dataset.correct === 'true') {
                    b.classList.add('bg-green-100', 'border-green-400', 'text-green-800', 'font-medium', 'dark:bg-green-900/50', 'dark:border-green-600', 'dark:text-green-300');
                }
            });
        }
    }
    </script>

    </x-app-layout>