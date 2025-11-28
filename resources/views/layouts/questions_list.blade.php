<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">
            QUESTIONNAIRE
        </h1>
        @if (session('success'))
            <div class="bg-green-500 text-black p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @else
            @foreach($questions as $index => $question)

                    <input type="hidden" name="answers[{{ $index }}][survey_id]" value="{{ $survey_id }}">
                    <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">
                    <div class="mt-6">
                        <h3 class="font-semibold">{{ $question->title }}</h3>
                                <label class="block">
                                    <button type="button"
                                            class="btn btn-primary bg-[#dbdbd7] border rounded p-2"
                                            onclick="window.location='{{ route('surveys.stats', [
                                            'organization' => $organization,
                                            'survey' => $survey_id,
                                            'question_id' => $question->id
                                        ]) }}'">
                                        Statistiques
                                    </button>
                                </label>


                    </div>
                @endforeach

        @endif
    </div>
</div>
</body>
</html>
