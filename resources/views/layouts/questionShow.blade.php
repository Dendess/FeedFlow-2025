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
                @if ($questions->question_type === 'single')
                    @foreach ($questions->options as $option)
                        <label class="block">
                            <input type="radio" name="answer" value="{{ $option }}">
                            {{ $option }}
                        </label>
                    @endforeach
                @endif

                @if ($questions->question_type === 'multiple')
                    @foreach ($questions->options as $option)
                        <label class="block">
                            <input type="checkbox" name="answer[]" value="{{ $option }}">
                            {{ $option }}
                        </label>
                    @endforeach
                @endif
            </div>
        </div>
    </body>
</html>
