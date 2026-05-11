<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-primary">Nouveau Domaine</h2>
                <p class="text-sm text-secondary mt-1">Crée un nouveau domaine technique</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-static p-6">
                <form method="POST" action="{{ route('domains.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Nom du domaine</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
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
                                    <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" {{ old('color') === $color ? 'checked' : '' }} />
                                    <div class="w-8 h-8 rounded-full border-2 border-transparent peer-checked:border-primary transition-all" style="background: {{ $color }};"></div>
                                </label>
                            @endforeach
                        </div>
                        @error('color')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('domains.index') }}" class="btn btn-ghost">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>