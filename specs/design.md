# DESIGN.md — InterviewPrep

## Stack UI
- **CSS Framework** : Tailwind CSS v3
- **Icons** : Heroicons ou Tabler Icons
- **Fonts** : Inter (Google Fonts)
- **Composants** : Blade components natifs Laravel

---

## Palette de couleurs

| Rôle | Couleur | Hex |
|------|---------|-----|
| Primary | Indigo | `#6366F1` |
| Success / Maîtrisé | Vert | `#10B981` |
| Warning / En cours | Ambre | `#F59E0B` |
| Danger / À revoir | Rouge | `#EF4444` |
| Background | Gris clair | `#F9FAFB` |
| Surface | Blanc | `#FFFFFF` |
| Text primary | Gris foncé | `#111827` |
| Text secondary | Gris moyen | `#6B7280` |
| Border | Gris léger | `#E5E7EB` |

---

## Dark Mode

### Palette Dark Mode

| Rôle | Couleur | Hex |
|------|---------|-----|
| Primary | Indigo | `#6366F1` |
| Primary Light | Indigo clair | `#818CF8` |
| Background | Gris très foncé | `#0F0F0F` |
| Surface | Gris foncé | `#1A1A1A` |
| Surface Elevated | Gris moyen foncé | `#252525` |
| Text primary | Blanc cassé | `#FAFAFA` |
| Text secondary | Gris clair | `#A3A3A3` |
| Text muted | Gris foncé | `#6B6B6B` |
| Border | Gris foncé | `#333333` |

### Implémentation Dark Mode

```html
{{-- Toggle Dark Mode Button --}}
<button id="theme-toggle" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition">
    <svg class="w-5 h-5 sun-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <svg class="w-5 h-5 moon-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>

{{-- Script pour toggle --}}
<script>
    const toggle = document.getElementById('theme-toggle');
    const html = document.documentElement;
    
    // Check preference
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
    }
    
    toggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        if (html.classList.contains('dark')) {
            localStorage.theme = 'dark';
        } else {
            localStorage.theme = 'light';
        }
    });
</script>
```

### CSS Dark Mode

```css
/* Tailwind config */
export default {
    darkMode: 'class',
    // ...
}

/* Custom dark styles */
.dark {
    --primary: #6366F1;
    --bg-primary: #0F0F0F;
    --bg-secondary: #1A1A1A;
    --bg-tertiary: #252525;
    --text-primary: #FAFAFA;
    --text-secondary: #A3A3A3;
    --border-color: #333333;
}

.dark body {
    background: var(--bg-primary);
    color: var(--text-primary);
}

.dark .card,
.dark .card-static {
    background: var(--bg-secondary);
    border-color: var(--border-color);
}

.dark .btn-primary {
    background: var(--primary);
    color: white;
}
```

---

## Badges statut

```html
{{-- À revoir --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
    À revoir
</span>

{{-- En cours --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
    En cours
</span>

{{-- Maîtrisé --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
    Maîtrisé
</span>
```

## Badges difficulté

```html
{{-- Junior --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
    Junior
</span>

{{-- Mid --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
    Mid
</span>

{{-- Senior --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
    Senior
</span>
```

---

## Layout principal — `layouts/app.blade.php`

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InterviewPrep — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo --}}
                <a href="/dashboard" class="flex items-center gap-2">
                    <span class="text-indigo-600 font-bold text-xl">InterviewPrep</span>
                </a>

                {{-- Nav links --}}
                <div class="flex items-center gap-6">
                    <a href="/domains"
                       class="text-sm text-gray-600 hover:text-indigo-600 transition">
                        Mes domaines
                    </a>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="text-sm text-gray-500 hover:text-red-500 transition">
                            Déconnexion
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-6xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Content --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

</body>
</html>
```

---

## Composants Blade réutilisables

### Bouton primaire
```html
{{-- resources/views/components/button.blade.php --}}
<button {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition']) }}>
    {{ $slot }}
</button>
```

### Card domaine
```html
{{-- resources/views/components/domain-card.blade.php --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-3">
            <span class="w-3 h-3 rounded-full" style="background: {{ $domain->color }}"></span>
            <h3 class="font-semibold text-gray-900">{{ $domain->name }}</h3>
        </div>
        <div class="flex gap-2">
            <a href="/domains/{{ $domain->id }}/edit" class="text-gray-400 hover:text-indigo-600 transition">
                ✏️
            </a>
            <form method="POST" action="/domains/{{ $domain->id }}">
                @csrf @method('DELETE')
                <button class="text-gray-400 hover:text-red-500 transition">🗑️</button>
            </form>
        </div>
    </div>
    <div class="flex gap-4 text-sm text-gray-500">
        <span>{{ $domain->concepts_count }} concepts</span>
        <span class="text-green-600">{{ $domain->maitrise_count }} maîtrisés</span>
    </div>
    <a href="/domains/{{ $domain->id }}/concepts"
       class="mt-4 block text-center text-sm text-indigo-600 hover:underline">
        Voir les concepts →
    </a>
</div>
```

### Bouton statut rapide
```html
{{-- resources/views/components/status-toggle.blade.php --}}
@php
    $next = match($concept->status) {
        'a_revoir' => 'en_cours',
        'en_cours' => 'maitrise',
        'maitrise' => 'a_revoir',
    };
    $colors = [
        'a_revoir' => 'bg-red-100 text-red-800 hover:bg-red-200',
        'en_cours' => 'bg-amber-100 text-amber-800 hover:bg-amber-200',
        'maitrise' => 'bg-green-100 text-green-800 hover:bg-green-200',
    ];
@endphp
<form method="POST" action="/concepts/{{ $concept->id }}/status">
    @csrf @method('PATCH')
    <input type="hidden" name="status" value="{{ $next }}">
    <button class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition {{ $colors[$concept->status] }}">
        {{ $concept->statusLabel }}
    </button>
</form>
```

---

## Pages principales

### Liste des domaines — `domains/index.blade.php`

```html
@extends('layouts.app')
@section('title', 'Mes domaines')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Mes domaines</h1>
    <a href="/domains/create"
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
        + Nouveau domaine
    </a>
</div>

@if($domains->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p class="text-lg">Aucun domaine pour l'instant.</p>
        <a href="/domains/create" class="text-indigo-600 hover:underline mt-2 block">
            Créer mon premier domaine →
        </a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($domains as $domain)
            <x-domain-card :domain="$domain" />
        @endforeach
    </div>
@endif
@endsection
```

### Liste des concepts — `concepts/index.blade.php`

```html
@extends('layouts.app')
@section('title', $domain->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="/domains" class="text-sm text-gray-400 hover:text-indigo-600">← Domaines</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $domain->name }}</h1>
    </div>
    <a href="/domains/{{ $domain->id }}/concepts/create"
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
        + Nouveau concept
    </a>
</div>

{{-- Filtres --}}
<div class="flex gap-2 mb-6">
    @foreach(['all' => 'Tous', 'a_revoir' => 'À revoir', 'en_cours' => 'En cours', 'maitrise' => 'Maîtrisé'] as $value => $label)
        <a href="?status={{ $value }}"
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition
                  {{ request('status', 'all') === $value
                     ? 'bg-indigo-600 text-white'
                     : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Table concepts --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Titre</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Niveau</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Statut</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($concepts as $concept)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <a href="/concepts/{{ $concept->id }}"
                       class="font-medium text-gray-900 hover:text-indigo-600">
                        {{ $concept->title }}
                    </a>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $concept->difficulty === 'junior' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $concept->difficulty === 'mid' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $concept->difficulty === 'senior' ? 'bg-indigo-100 text-indigo-800' : '' }}">
                        {{ $concept->difficultyLabel }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <x-status-toggle :concept="$concept" />
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="/concepts/{{ $concept->id }}/edit"
                       class="text-gray-400 hover:text-indigo-600 mr-2">✏️</a>
                    <form method="POST" action="/concepts/{{ $concept->id }}" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-gray-400 hover:text-red-500">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
```

---

## Formulaires

### Créer / Modifier un concept
```html
<form method="POST" action="{{ $action }}">
    @csrf
    @isset($concept) @method('PUT') @endisset

    <div class="space-y-4">
        {{-- Titre --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
            <input type="text" name="title"
                   value="{{ old('title', $concept->title ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                          @error('title') border-red-400 @enderror">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Explication --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Explication</label>
            <textarea name="explanation" rows="6"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                             @error('explanation') border-red-400 @enderror">{{ old('explanation', $concept->explanation ?? '') }}</textarea>
            @error('explanation')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Difficulté --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
            <select name="difficulty"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach(['junior' => 'Junior', 'mid' => 'Mid', 'senior' => 'Senior'] as $value => $label)
                    <option value="{{ $value }}"
                        {{ old('difficulty', $concept->difficulty ?? '') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg transition">
            {{ isset($concept) ? 'Mettre à jour' : 'Créer le concept' }}
        </button>
    </div>
</form>
```

---

## Installation Tailwind CSS

```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

`tailwind.config.js` :
```js
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
```

`resources/css/app.css` :
```css
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
@tailwind base;
@tailwind components;
@tailwind utilities;
```

```bash
npm run dev
```