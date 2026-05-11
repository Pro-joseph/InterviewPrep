<x-guest-layout>
    <div class="mb-8">
        <h2 class="font-display text-2xl font-semibold text-primary mb-2">Bon retour</h2>
        <p class="text-secondary">Connecte-toi pour continuer ta préparation</p>
    </div>

    <x-auth-session-status class="mb-6 p-4 rounded-lg bg-primary/10 text-primary text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
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
                autocomplete="current-password"
                class="form-input"
                placeholder="••••••••"
            />
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 rounded border-default bg-tertiary text-primary focus:ring-primary focus:ring-offset-0"
                />
                <span class="text-sm text-secondary">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-full">
            Se connecter
        </button>
    </form>

    <div class="mt-8 text-center">
        <span class="text-sm text-secondary">Pas encore de compte ? </span>
        <a href="{{ route('register') }}" class="text-sm text-primary font-semibold hover:underline">
            Créer un compte
        </a>
    </div>
</x-guest-layout>