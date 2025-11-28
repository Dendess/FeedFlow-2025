<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $survey->title }}
        </h2>
    </x-slot>

    @push('styles')
        <link href="{{ asset('css/surveys.css') }}" rel="stylesheet">
    @endpush

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Détails du sondage</h3>

                    <div class="mb-4">
                        <strong>Description:</strong>
                        <p class="text-gray-700">{{ $survey->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-sm text-gray-600">
                        <div>
                            <div class="font-medium">Date de début</div>
                            <div>{{ optional($survey->start_date)->format('d/m/Y H:i') ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="font-medium">Date de fin</div>
                            <div>{{ optional($survey->end_date)->format('d/m/Y H:i') ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800">{{ $survey->is_anonymous ? 'Anonyme' : 'Identifié' }}</span>
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <a href="{{ route('surveys.index') }}" class="text-gray-600 hover:text-gray-900">Retour à la liste</a>

                        @can('update', $survey)
                            <a href="{{ route('surveys.edit', $survey) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-3 rounded">Éditer</a>
                        @endcan

                        @can('viewResults', $survey)
                            <a href="{{ route('surveys.results', $survey) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-3 rounded">Résultats</a>
                        @endcan

                        @can('view', $survey)
                            <a href="{{ route('surveys.answers.form', $survey) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 rounded">Répondre</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
