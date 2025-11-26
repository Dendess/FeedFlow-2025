<x-app-layout>
    <x-slot name="header">
        <div class="org-header">
            <h2 class="org-header-title">{{ __('Nouvelle Organisation') }}</h2>
            <a href="{{ route('organizations.index') }}" class="org-action-link">Retour</a>
        </div>
    </x-slot>

    <div class="org-page-section">
        <div class="org-container">
            @if($errors->any())
                <div class="org-alert-error">
                    <ul class="org-error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @include('organizations._form', [
                'organization' => null,
                'action' => route('organizations.store'),
                'method' => 'POST',
                'submit' => 'Créer l\'organisation',
            ])
        </div>
    </div>
</x-app-layout>