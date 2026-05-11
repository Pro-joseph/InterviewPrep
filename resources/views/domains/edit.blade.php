<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-primary">Modifier le Domaine</h2>
                <p class="text-sm text-secondary mt-1">{{ $domain->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-static p-6">
                <form method="POST" action="{{ route('domains.update', $domain) }}" id="updateForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">Nom du domaine</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $domain->name) }}"
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
                                    <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" {{ $domain->color === $color ? 'checked' : '' }} />
                                    <div class="w-8 h-8 rounded-full border-2 border-transparent peer-checked:border-primary transition-all" style="background: {{ $color }};"></div>
                                </label>
                            @endforeach
                        </div>
                        @error('color')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-default">
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            Supprimer
                        </button>

                        <div class="flex gap-3">
                            <a href="{{ route('domains.index') }}" class="btn btn-ghost">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="{{ route('domains.destroy', $domain) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete() {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce domaine ?')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</x-app-layout>