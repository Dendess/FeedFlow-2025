<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg text-gray-800">
                Résultats — {{ $survey->title }}
            </h2>
            <a href="{{ route('surveys.show', $survey) }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">Retour</a>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="bg-indigo-600 text-white px-6 py-4">
                    <h3 class="text-lg font-semibold">{{ $survey->title }}</h3>
                    <div class="mt-1 text-sm text-indigo-100">
                        {{ $survey->questions->count() }} question(s) • 
                        {{ \App\Models\SurveyAnswer::whereIn('survey_question_id', $survey->questions->pluck('id'))->distinct('user_id')->count('user_id') }} répondant(s)
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    @foreach($survey->questions as $qIndex => $question)
                        @php
                            $allAnswers = \App\Models\SurveyAnswer::where('survey_question_id', $question->id)
                                ->with('user')
                                ->orderBy('created_at', 'desc')
                                ->get();
                            
                            $counts = [];
                            foreach ($allAnswers as $ans) {
                                $decoded = json_decode($ans->answer, true);
                                if (is_array($decoded)) {
                                    foreach ($decoded as $item) { 
                                        $counts[$item] = ($counts[$item] ?? 0) + 1; 
                                    }
                                } else {
                                    $counts[$ans->answer] = ($counts[$ans->answer] ?? 0) + 1;
                                }
                            }
                            $labels = array_keys($counts);
                            $totals = array_values($counts);
                        @endphp

                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <div class="flex items-start gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-medium">{{ $qIndex + 1 }}</span>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">{{ $question->title }}</h4>
                                        <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded
                                            @if($question->question_type === 'text') bg-blue-100 text-blue-700
                                            @elseif($question->question_type === 'scale') bg-purple-100 text-purple-700
                                            @else bg-green-100 text-green-700
                                            @endif">
                                            @if($question->question_type === 'text') Texte libre
                                            @elseif($question->question_type === 'scale') Échelle
                                            @else Choix multiples
                                            @endif
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $allAnswers->count() }} réponse(s)</span>
                                </div>
                            </div>

                            <div class="p-4">
                                @if($allAnswers->isEmpty())
                                    <p class="text-sm text-gray-500 italic">Aucune réponse</p>
                                @else
                                    <!-- Debug info (à supprimer après test) -->
                                    <div class="text-xs text-gray-500 mb-2">
                                        Type: {{ $question->question_type }} | Réponses: {{ $allAnswers->count() }} | Labels: {{ count($labels) }}
                                    </div>
                                    
                                    @if($question->question_type !== 'text' && count($labels) > 0)
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                                            <!-- Graphique -->
                                            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 flex items-center justify-center">
                                                <div class="w-full max-w-sm">
                                                    <canvas id="chart-{{ $question->id }}" class="mx-auto"></canvas>
                                                </div>
                                            </div>
                                            
                                            <!-- Synthèse textuelle -->
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="text-xs font-medium text-gray-700 mb-3">Synthèse :</div>
                                                <div class="space-y-2">
                                                    @foreach($counts as $label => $total)
                                                        <div class="flex items-center gap-2">
                                                            <div class="flex-1 flex items-center gap-2">
                                                                <div class="h-6 bg-indigo-200 rounded transition-all" style="width: {{ ($total / $allAnswers->count()) * 100 }}%"></div>
                                                                <span class="text-xs text-gray-700 font-medium">{{ $label }}</span>
                                                            </div>
                                                            <div class="text-right">
                                                                <span class="text-xs font-bold text-gray-900">{{ $total }}</span>
                                                                <span class="text-xs text-gray-500 ml-1">({{ round(($total / $allAnswers->count()) * 100, 1) }}%)</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="text-xs font-medium text-gray-700 mb-2">Réponses individuelles :</div>
                                    <div class="space-y-2">
                                        @foreach($allAnswers as $answer)
                                            <div class="flex items-start gap-3 text-sm bg-gray-50 rounded p-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-xs font-medium text-indigo-700">
                                                        @if($answer->user && !$survey->is_anonymous)
                                                            {{ substr($answer->user->first_name ?? 'U', 0, 1) }}{{ substr($answer->user->last_name ?? 'U', 0, 1) }}
                                                        @else
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-medium text-gray-900 text-xs">
                                                        @if($answer->user && !$survey->is_anonymous)
                                                            {{ $answer->user->first_name }} {{ $answer->user->last_name }}
                                                        @else
                                                            Anonyme
                                                        @endif
                                                    </div>
                                                    <div class="mt-1 text-gray-700">
                                                        @php
                                                            $decoded = json_decode($answer->answer, true);
                                                        @endphp
                                                        @if(is_array($decoded))
                                                            {{ implode(', ', $decoded) }}
                                                        @else
                                                            {{ $answer->answer }}
                                                        @endif
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-500">
                                                        {{ $answer->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($question->question_type !== 'text' && !$allAnswers->isEmpty() && count($labels) > 0)
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const labels_{{ $question->id }} = @json(array_map('strval', $labels));
                                    const totals_{{ $question->id }} = @json($totals);

                                    console.log('Question {{ $question->id }}: Trying to create chart');
                                    console.log('Labels:', labels_{{ $question->id }});
                                    console.log('Totals:', totals_{{ $question->id }});

                                    const ctx = document.getElementById('chart-{{ $question->id }}');
                                    console.log('Canvas element:', ctx);
                                    console.log('Chart available:', typeof Chart !== 'undefined');
                                    
                                    if (ctx && typeof Chart !== 'undefined') {
                                        try {
                                            new Chart(ctx, {
                                            type: 'pie',
                                            data: {
                                                labels: labels_{{ $question->id }},
                                                datasets: [{
                                                    label: 'Nombre de réponses',
                                                    data: totals_{{ $question->id }},
                                                    backgroundColor: labels_{{ $question->id }}.map((_, i) => {
                                                        const hue = (i * 360 / labels_{{ $question->id }}.length);
                                                        return `hsl(${hue}, 70%, 60%)`;
                                                    }),
                                                    borderColor: '#fff',
                                                    borderWidth: 2
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: true,
                                                plugins: {
                                                    legend: {
                                                        position: 'bottom',
                                                        labels: {
                                                            padding: 12,
                                                            font: {
                                                                size: 11
                                                            }
                                                        }
                                                    },
                                                    tooltip: {
                                                        callbacks: {
                                                            label: function(context) {
                                                                const label = context.label || '';
                                                                const value = context.parsed || 0;
                                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                                const percentage = ((value / total) * 100).toFixed(1);
                                                                return `${label}: ${value} (${percentage}%)`;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                        console.log('Chart created successfully for question {{ $question->id }}');
                                    } catch (error) {
                                        console.error('Error creating chart for question {{ $question->id }}:', error);
                                    }
                                } else {
                                    console.error('Cannot create chart: ctx=' + !!ctx + ', Chart=' + (typeof Chart !== 'undefined'));
                                }
                                });
                            </script>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
