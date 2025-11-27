<x-app-layout>
    <x-slot name="header">
        <div class="org-header">
            <h2 class="org-header-title">{{ __('Modifier l\'organisation') }}</h2>
            <a href="{{ route('organizations.index') }}" class="org-action-link">Retour</a>
        </div>
    </x-slot>

    <div class="org-page-section">
        <div class="org-container">
            @if(session('success'))
                <div class="org-alert-success">{{ session('success') }}</div>
            @endif

            @include('organizations._form', [
                'organization' => $organization,
                'action' => route('organizations.update', $organization),
                'method' => 'PUT',
                'submit' => 'Enregistrer les modifications',
            ])
        </div>
    </div>
</x-app-layout>
