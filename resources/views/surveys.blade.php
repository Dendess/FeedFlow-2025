<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Sondages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Messages de succès -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Liste des sondages -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Liste des sondages</h3>

                    @if($surveys->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Début</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anonyme</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($surveys as $survey)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $survey->title }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($survey->description, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($survey->start_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($survey->end_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($survey->is_anonymous)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Oui</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Non</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="editSurvey({{ $survey->id }}, '{{ $survey->title }}', '{{ $survey->description }}', '{{ $survey->start_date }}', '{{ $survey->end_date }}', {{ $survey->is_anonymous ? 'true' : 'false' }})"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Éditer
                                            </button>
                                            <form action="{{ route('surveys.destroy', $survey) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce sondage ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Aucun sondage créé pour le moment.</p>
                    @endif
                </div>
            </div>

            <!-- Formulaire de création/édition -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4" id="form-title">Créer un nouveau sondage</h3>

                    <form id="survey-form" method="POST" action="{{ route('surveys.store') }}">
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="form-method">

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                            <input type="text" name="title" id="title" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('title') }}">
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="4" required
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                                <input type="datetime-local" name="start_date" id="start_date" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('start_date') }}">
                                @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                                <input type="datetime-local" name="end_date" id="end_date" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('end_date') }}">
                                @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    {{ old('is_anonymous') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Sondage anonyme</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                                Enregistrer
                            </button>
                            <button type="button" onclick="resetForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function editSurvey(id, title, description, startDate, endDate, isAnonymous) {
            // Changer le titre du formulaire
            document.getElementById('form-title').textContent = 'Éditer le sondage';

            // Changer l'action et la méthode du formulaire
            document.getElementById('survey-form').action = `/surveys/${id}`;
            document.getElementById('form-method').value = 'PUT';

            // Remplir les champs
            document.getElementById('title').value = title;
            document.getElementById('description').value = description;
            document.getElementById('start_date').value = startDate.slice(0, 16);
            document.getElementById('end_date').value = endDate.slice(0, 16);
            document.getElementById('is_anonymous').checked = isAnonymous;

            // Scroll vers le formulaire
            document.getElementById('survey-form').scrollIntoView({ behavior: 'smooth' });
        }

        function resetForm() {
            document.getElementById('form-title').textContent = 'Créer un nouveau sondage';
            document.getElementById('survey-form').action = '{{ route('surveys.store') }}';
            document.getElementById('form-method').value = 'POST';
            document.getElementById('survey-form').reset();
        }
    </script>
</x-app-layout>
