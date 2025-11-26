<x-app-layout>
    <x-slot name="header">
        <div class="org-header">
            <h2 class="org-header-title">{{ $organization->name }}</h2>
            <div class="org-header-actions">
                <a href="{{ route('organizations.edit', $organization) }}" class="org-btn-edit">✏️ Modifier</a>
                <a href="{{ route('organizations.index') }}" class="org-action-link">Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="org-page-section">
        <div class="org-container">
            <div class="org-card">
                <h3 class="org-card-title">Détails</h3>
                <p class="org-card-desc"><strong>Nom :</strong> {{ $organization->name }}</p>
                <p class="org-card-desc"><strong>Créée le :</strong> {{ $organization->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
