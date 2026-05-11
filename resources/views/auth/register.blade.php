<x-guest-layout>
    <div class="mb-8">
        <h2 class="font-display text-2xl font-semibold text-primary mb-2">Créer un compte</h2>
        <p class="text-secondary">Commence ta préparation aux entretiens techniques</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Nom complet</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="form-input"
                placeholder="Ton nom"
            />
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                class="form-input"
                placeholder="ton@email.com"
            />
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="form-input"
                placeholder="••••••••"
            />
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="form-input"
                placeholder="••••••••"
            />
            @error('password_confirmation')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full mt-4">
            Créer mon compte
        </button>
    </form>

    <div class="mt-8 text-center">
        <span class="text-sm text-secondary">Déjà un compte ? </span>
        <a href="{{ route('login') }}" class="text-sm text-primary font-semibold hover:underline">
            Se connecter
        </a>
    </div>
</x-guest-layout>