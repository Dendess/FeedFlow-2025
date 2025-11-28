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
                    Question
                </h1>
            </div>
            <div class="check-or-rad">
                @if($questions->title)
                    <p>{{$questions->title}}</p>
                @endif
                @if ($questions->question_type === 'text')
                        <input
                            type="text"
                            name="answer"
                            class="border p-2 w-full"
                            placeholder="Votre réponse..."
                        >
                @endif
                @if ($questions->question_type === 'scale')
                        <input
                            type="range"
                            name="answer"
                            min="1"
                            max="10"
                            value="5"
                            class="w-full"
                        >
                        <div class="text-sm text-gray-600 flex justify-between">
                            <span>1</span>
                            <span>10</span>
                        </div>
                @endif



                    {{-- Options : une seule réponse --}}
                    @if ($questions->question_type === 'options_single')
                        @foreach ($questions->options as $option)
                            <label class="block">
                                <input
                                    type="radio"
                                    name="answer"
                                    value="{{ $option }}"
                                >
                                {{ $option }}
                            </label>
                        @endforeach
                    @endif

                    {{-- Options : plusieurs réponses --}}
                    @if ($questions->question_type === 'options_multiple')
                        @foreach ($questions->options as $option)
                            <label class="block">
                                <input
                                    type="checkbox"
                                    name="answer[]"
                                    value="{{ $option }}"
                                >
                                {{ $option }}
                            </label>
                        @endforeach
                    @endif
            </div>
        </div>
    </body>
</html>
