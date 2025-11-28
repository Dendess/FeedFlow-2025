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
            Création question du sondage
        </h1>
        @if (session('success'))
            <div class="bg-green-500 text-black p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @else
            <form method="POST" action="{{ route('answer.store') }}">
                @csrf
                @foreach($questions as $index => $question)

                    <input type="hidden" name="answers[{{ $index }}][survey_id]" value="{{ $survey_id }}">
                    <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">
                    {{ $question->title }}
                    @if ($question->question_type === 'single')
                        @foreach ($question->options as $option)
                            <label class="block">
                                <input type="radio" name="answers[{{ $index }}][answer]" value="{{ $option }}">
                                {{ $option }}
                            </label>
                        @endforeach

                    @elseif ($question->question_type === 'multiple')
                        @foreach ($question->options as $option)
                            <label class="block">
                                <input type="checkbox" name="answers[{{ $index }}][answer][]" value="{{ $option }}">
                                {{ $option }}
                            </label>
                        @endforeach
                    @endif
                @endforeach

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-4">
                        Répondre
                    </x-primary-button>
                </div>
            </form>
        @endif
    </div>
</div>
</body>
</html>
