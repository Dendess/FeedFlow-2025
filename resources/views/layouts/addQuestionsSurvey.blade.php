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
        <form action="{{ route('question.store') }}" method="POST">
            @csrf

            <input type="text" id="title" name="title" placeholder="Titre">
            <input type="text" id="options" name="options[]" placeholder="Option de réponse">
            <input type="checkbox" id="question_type" name="question_type" value="multiple">

            <button type="submit"> Valider </button>
        </form>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
