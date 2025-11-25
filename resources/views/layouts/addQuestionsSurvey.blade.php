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
        <form>
            <h3>Question : </h3>
            <br>
            <input type="text" name="titre_question" placeholder="Titre">
            <br>
            <br>
            <input type="text" name="option_question" placeholder="Option de réponse">
            <br>
            <br>
            <input type="checkbox" name="multiple_answer_radio">Plusieurs question possible</input>
        </form>
        <br>
        <button
            onclick=""
            class="mt-4 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
        >
            Validée
        </button>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
