<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg text-gray-800">{{ $survey->title ?? 'Répondre au sondage' }}</h2>
            <a href="{{ route('surveys.show', $survey_id) }}" 
               class="text-sm text-gray-600 hover:text-indigo-600 transition">
                Retour
            </a>
        </div>
    </x-slot>

    <style>
        .question-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .question-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .option-label {
            transition: all 0.2s;
        }
        .option-input:checked + .option-label {
            border-color: #4f46e5;
            background-color: #eef2ff;
        }
        .scale-input {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: linear-gradient(to right, #e0e7ff 0%, #4f46e5 100%);
            outline: none;
        }
        .scale-input::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #4f46e5;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .scale-input::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #4f46e5;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            border: none;
        }
    </style>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded">
                    <div class="font-medium mb-1">Erreurs détectées</div>
                    <ul class="text-sm list-disc pl-5 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($questions->isEmpty())
                <div class="bg-white shadow rounded-lg p-8 text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Aucune question disponible</h3>
                    <p class="text-sm text-gray-600 mb-4">Ce sondage ne contient pas encore de questions.</p>
                    @can('update', App\Models\Survey::find($survey_id))
                        <a href="{{ route('surveys.questions.create') }}?survey={{ $survey_id }}" 
                           class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded transition">
                            Ajouter des questions
                        </a>
                    @endcan
                </div>
            @else
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="bg-indigo-600 text-white px-6 py-4">
                        <h3 class="text-lg font-semibold">{{ $survey->title }}</h3>
                        @if($survey->description)
                            <p class="text-sm text-indigo-100 mt-1">{{ $survey->description }}</p>
                        @endif
                        <div class="mt-2 text-xs text-indigo-200">
                            {{ $questions->count() }} question(s)
                        </div>
                    </div>

                    <form method="POST" action="{{ route('surveys.answers.store') }}" class="p-6">
                        @csrf

                        <div class="space-y-4">
                            @foreach($questions as $index => $question)
                            <div class="question-card border border-gray-200 rounded-lg p-4">
                                <input type="hidden" name="answers[{{ $index }}][survey_id]" value="{{ $survey_id }}">
                                <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">

                                <div class="flex items-start gap-3 mb-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-medium">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $question->title }}</h4>
                                        <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded
                                            @if($question->question_type === 'text') bg-blue-100 text-blue-700
                                            @elseif($question->question_type === 'scale') bg-purple-100 text-purple-700
                                            @elseif($question->question_type === 'option_single') bg-green-100 text-green-700
                                            @elseif($question->question_type === 'option_multiple') bg-orange-100 text-orange-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            @if($question->question_type === 'text') Texte
                                            @elseif($question->question_type === 'scale') Échelle
                                            @elseif($question->question_type === 'option_single') Choix unique
                                            @elseif($question->question_type === 'option_multiple') Choix multiples
                                            @else Choix
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="ml-9 space-y-2">
                                    @if ($question->question_type === 'text')
                                        <textarea name="answers[{{ $index }}][answer]" 
                                                  rows="2"
                                                  class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" 
                                                  placeholder="Votre réponse..."
                                                  required></textarea>
                                    
                                    @elseif ($question->question_type === 'scale')
                                        @php
                                            $scaleData = is_array($question->options) ? $question->options : (is_string($question->options) ? json_decode($question->options, true) : ['min' => 1, 'max' => 10]);
                                            $min = $scaleData['min'] ?? 1;
                                            $max = $scaleData['max'] ?? 10;
                                            $defaultValue = floor(($min + $max) / 2);
                                        @endphp
                                        <div class="bg-gray-50 rounded p-3">
                                            <div class="flex items-center justify-between mb-2 text-xs text-gray-600">
                                                <span>{{ $min }}</span>
                                                <span class="text-lg font-semibold text-indigo-600" id="scale-value-{{ $index }}">{{ $defaultValue }}</span>
                                                <span>{{ $max }}</span>
                                            </div>
                                            <input type="range" 
                                                   name="answers[{{ $index }}][answer]" 
                                                   min="{{ $min }}" 
                                                   max="{{ $max }}" 
                                                   value="{{ $defaultValue }}" 
                                                   class="scale-input"
                                                   oninput="document.getElementById('scale-value-{{ $index }}').textContent = this.value"
                                                   required>
                                        </div>
                                    
                                    @elseif ($question->question_type === 'option_single')
                                        @php
                                            $options = is_array($question->options) ? $question->options : (is_string($question->options) ? json_decode($question->options, true) : []);
                                        @endphp
                                        @foreach ($options as $optIndex => $option)
                                            <label class="flex items-center gap-2 p-2.5 border border-gray-300 rounded cursor-pointer hover:bg-gray-50 option-label">
                                                <input type="radio" 
                                                       name="answers[{{ $index }}][answer]" 
                                                       value="{{ $option }}" 
                                                       class="option-input w-4 h-4 text-indigo-600"
                                                       required>
                                                <span class="text-sm text-gray-800">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    
                                    @elseif ($question->question_type === 'option_multiple')
                                        @php
                                            $options = is_array($question->options) ? $question->options : (is_string($question->options) ? json_decode($question->options, true) : []);
                                        @endphp
                                        @foreach ($options as $optIndex => $option)
                                            <label class="flex items-center gap-2 p-2.5 border border-gray-300 rounded cursor-pointer hover:bg-gray-50 option-label">
                                                <input type="checkbox" 
                                                       name="answers[{{ $index }}][answer][]" 
                                                       value="{{ $option }}" 
                                                       class="option-input w-4 h-4 text-indigo-600 rounded">
                                                <span class="text-sm text-gray-800">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    
                                    @elseif ($question->question_type === 'single' || $question->question_type === 'options_single')
                                        @php
                                            $options = is_array($question->options) ? $question->options : (is_string($question->options) ? json_decode($question->options, true) : []);
                                        @endphp
                                        @foreach ($options as $optIndex => $option)
                                            <label class="flex items-center gap-2 p-2.5 border border-gray-300 rounded cursor-pointer hover:bg-gray-50 option-label">
                                                <input type="radio" 
                                                       name="answers[{{ $index }}][answer]" 
                                                       value="{{ $option }}" 
                                                       class="option-input w-4 h-4 text-indigo-600"
                                                       required>
                                                <span class="text-sm text-gray-800">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    
                                    @else
                                        @php
                                            $options = is_array($question->options) ? $question->options : (is_string($question->options) ? json_decode($question->options, true) : []);
                                        @endphp
                                        @foreach ($options as $optIndex => $option)
                                            <label class="flex items-center gap-2 p-2.5 border border-gray-300 rounded cursor-pointer hover:bg-gray-50 option-label">
                                                <input type="checkbox" 
                                                       name="answers[{{ $index }}][answer][]" 
                                                       value="{{ $option }}" 
                                                       class="option-input w-4 h-4 text-indigo-600 rounded">
                                                <span class="text-sm text-gray-800">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200 flex items-center justify-end gap-4">
                            <span class="text-xs text-gray-500">{{ $questions->count() }} question(s)</span>
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded transition shadow-sm hover:shadow-md">
                                Soumettre mes réponses
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
