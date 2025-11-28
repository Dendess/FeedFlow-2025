<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">{{ $organization->name }}</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('surveys.index') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Mes formulaires
                </a>
                @can('update', $organization)
                    <a href="{{ route('organizations.edit', $organization) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition">
                        Modifier infos
                    </a>
                @endcan
                <a href="{{ route('organizations.index') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Détails de l'organisation -->
                <section class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Détails</h3>
                        <p class="text-sm text-gray-600 mt-1">Informations générales de l'organisation.</p>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="flex justify-between items-start">
                            <dt class="text-sm font-medium text-gray-500">Propriétaire</dt>
                            <dd class="text-sm text-gray-900 text-right">{{ optional($organization->owner)->first_name ?? 'Inconnu' }} {{ optional($organization->owner)->last_name }}</dd>
                        </div>
                        <div class="flex justify-between items-start">
                            <dt class="text-sm font-medium text-gray-500">Créée le</dt>
                            <dd class="text-sm text-gray-900">{{ $organization->created_at->format('d/m/Y') }}</dd>
                        </div>
                    </div>
                </section>

                <!-- Formulaires de l'organisation -->
                <section class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Formulaires de l'organisation</h3>
                            <p class="text-sm text-gray-600 mt-1">Liste des sondages déjà créés.</p>
                        </div>
                        @can('create', App\Models\Survey::class)
                            <a href="{{ route('surveys.create', ['organization' => $organization->id]) }}" class="px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition whitespace-nowrap">
                                Créer un sondage
                            </a>
                        @endcan
                    </div>

                    @if($organization->surveys->isEmpty())
                        <p class="px-6 py-8 text-center text-gray-500 text-sm italic">Aucun sondage pour le moment.</p>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach($organization->surveys as $survey)
                                <div class="px-6 py-4 hover:bg-gray-50 transition">
                                    <div class="flex items-center justify-between gap-4">
                                        <a href="{{ route('surveys.show', $survey) }}" class="flex-1 min-w-0">
                                            <div class="font-medium text-gray-900 truncate">{{ $survey->title }}</div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                Créé le {{ $survey->created_at->format('d/m/Y') }} ·
                                                @if($survey->is_anonymous)
                                                    Anonyme
                                                @else
                                                    Identifié
                                                @endif
                                            </div>
                                        </a>

                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            @can('view', $survey)
                                                <a href="{{ route('surveys.answers.form', $survey) }}" class="px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                                    Répondre
                                                </a>
                                            @endcan
                                            <a href="{{ route('surveys.show', $survey) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <!-- Membres de l'équipe (full width) -->
                <section class="bg-white shadow-sm rounded-lg overflow-hidden lg:col-span-2">
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Membres de l'équipe</h3>
                        <p class="text-sm text-gray-600 mt-1">Visualisez et gérez l'accès à l'organisation.</p>
                    </div>

                    @can('update', $organization)
                        <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
                            <form action="{{ route('organizations.users.store', $organization) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                                        <input type="email" name="email" id="email" placeholder="email@exemple.com" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                                    </div>
                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                                        <select name="role" id="role" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                            <option value="member">Membre</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="flex items-end">
                                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                            Ajouter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endcan

                    <div class="divide-y divide-gray-200">
                        @foreach($organization->users as $user)
                            <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-semibold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <div class="text-sm text-gray-600 truncate">{{ $user->email }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 flex-shrink-0">
                                    @if($organization->user_id === $user->id)
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Propriétaire</span>
                                    @elseif($user->pivot->role === 'admin')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Admin</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">Membre</span>
                                    @endif

                                    @can('update', $organization)
                                        @if($organization->user_id !== $user->id && $user->id !== auth()->id())
                                            <form action="{{ route('organizations.users.destroy', [$organization, $user]) }}" method="POST" 
                                                  onsubmit="return confirm('Voulez-vous vraiment retirer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                                                    Retirer
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>