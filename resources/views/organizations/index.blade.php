<x-app-layout>
    <x-slot name="header">
        <div class="org-header">
            <h2 class="org-header-title">{{ __('Mes Organisations') }}</h2>
            <a href="{{ route('organizations.create') }}" class="org-btn-primary">+ Nouvelle Organisation</a>
        </div>
    </x-slot>

    <div class="org-page-section">
        <div class="org-container">
            
            @if(session('success'))
                <div class="org-alert-success" role="alert">{{ session('success') }}</div>
            @endif

            @if($organizations->isEmpty())
                <div class="org-card org-card-center">
                    <h3 class="org-card-title">Vous n'avez pas encore d'organisation</h3>
                    <p class="org-card-desc">Créez une organisation pour commencer à publier des sondages et inviter des membres.</p>
                    <a href="{{ route('organizations.create') }}" class="org-btn-primary">+ Nouvelle Organisation</a>
                </div>
            @else
                <div class="org-grid">
                @foreach($organizations as $org)
                    <div class="org-card">
                        <h3 class="org-card-title">{{ $org->name }}</h3>
                        <div class="org-card-footer">
                            <div class="org-card-left">

                                <a href="{{ route('organizations.show', $org) }}" class="org-small-link">Voir</a>
                            </div>

                            @can('update', $org)
                                <div>
                                    <a href="{{ route('organizations.edit', $org) }}" title="Modifier" class="org-btn-edit">✏️ Modifier</a>

                                    <form action="{{ route('organizations.destroy', $org) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette organisation ?');" class="org-inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="org-btn-delete">🗑️ Supprimer</button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>