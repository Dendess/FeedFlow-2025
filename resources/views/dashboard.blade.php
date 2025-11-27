<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="org-card-row">
                <!-- Carte : Gérer mes organisations -->
                <a href="{{ route('organizations.index') }}" class="org-card" title="Gérer mes organisations">
                    <div class="org-card-meta">
                        <div class="org-card-icon org-card-icon--primary">
                            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h3 class="org-card-title">Gérer mes organisations</h3>
                            <p class="org-card-desc">Accédez à la liste de vos organisations pour les modifier, les supprimer ou changer l'organisation active.</p>
                        </div>
                    </div>
                </a>

                <!-- Carte : Nouvelle Organisation -->
                <a href="{{ route('organizations.create') }}" class="org-card" title="Créer une organisation">
                    <div class="org-card-meta">
                        <div class="org-card-icon org-card-icon--success">
                            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <div>
                            <h3 class="org-card-title">Nouvelle Organisation</h3>
                            <p class="org-card-desc">Créez une nouvelle structure pour commencer à publier vos sondages et collecter des avis.</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

