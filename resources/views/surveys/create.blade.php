<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un sondage') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $selectedOrganization = old('organization_id', request('organization'));
                    @endphp

                    <form action="{{ route('surveys.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="rounded-md bg-blue-50 border border-blue-100 p-4">
                            <p class="text-sm text-blue-800">Astuce : créez d'abord le sondage, puis <strong>ajoutez les questions</strong> avec l'outil dédié.</p>
                            <div class="mt-3">
                                <a id="open-questions-tool" target="_blank" rel="noopener" href="{{ route('surveys.questions.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded">Ouvrir l'outil de questions</a>
                                <span class="ml-3 text-sm text-gray-600">(s'ouvre dans un nouvel onglet)</span>
                            </div>
                        </div>

                        <div>
                            <label for="organization_id" class="block text-sm font-medium text-gray-700 mb-2">Organisation</label>
                            <select name="organization_id" id="organization_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Sélectionnez une organisation --</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}" {{ (int) $selectedOrganization === $org->id ? 'selected' : '' }}>
                                        {{ $org->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                                <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                                <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_anonymous" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('is_anonymous') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Sondage anonyme</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded shadow-sm hover:shadow-md">
                                Enregistrer
                            </button>
                            <a href="{{ route('surveys.index') }}" class="text-gray-600 hover:text-gray-900">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    (function(){
        const orgSelect = document.getElementById('organization_id');
        const openTool = document.getElementById('open-questions-tool');
        if(!orgSelect || !openTool) return;
        function updateHref(){
            const base = openTool.getAttribute('href').split('?')[0];
            const val = orgSelect.value;
            if(val) openTool.setAttribute('href', base + '?organization=' + encodeURIComponent(val));
            else openTool.setAttribute('href', base);
        }
        orgSelect.addEventListener('change', updateHref);
        // initial
        updateHref();
    })();
</script>
@endpush

@push('styles')
    <link href="{{ asset('css/surveys.css') }}" rel="stylesheet">
@endpush
