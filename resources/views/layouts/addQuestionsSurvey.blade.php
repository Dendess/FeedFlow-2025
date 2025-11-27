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
                    <h3>Question : </h3>
                    <br>
                    <input type="text" id="title" name="title" placeholder="Titre">
                    <br>
                    <br>
                    <div class="answers-options" id="options-container">
                        <input type="text" id="options" name="options[]" placeholder="Option de réponse">
                    </div>
                    <br>
                    <br>
                    <button
                        class="mt-4 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                        id ="add-option-button"
                        type="button">
                        +
                    </button>

                    <button
                        class="mt-4 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                        id="delete-option-button"
                        type="button"
                        >
                        -
                    </button>
                    <br>
                    <br>
                    <input type="checkbox" id="question_type" name="question_type">Plusieurs réponses possibles</input>
                    <br>
                    <br>
                    <input type="range" id="difficulty" name="difficulty" min="1" max="10" value="5" oninput="difficultyValue.value = difficulty.value">
                    <output id="difficultyValue">5</output>
                    <br>
                    <button type="submit"
                        class="mt-4 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                    >
                        Validée
                    </button>
                </form>
            </div>
        </div>

        <script>
            // ajouter une option de réponse

            function addInput () {
                const inputOptions = document.getElementById("add-option-button");
                const container = document.getElementById("options-container");
                inputOptions.addEventListener('click' , function () {
                    const newInput = document.createElement('input');
                    newInput.type = 'text';
                    newInput.name = 'options[]';
                    newInput.placeholder = 'Option de réponse';
                    container.appendChild(newInput)
                })
            }

            // supprimer une option de réponse

            function deleteInput () {
                const inputOptions = document.getElementById("delete-option-button");
                const container = document.getElementById("options-container");
                //const inputDelete = document.getElementById("options")
                inputOptions.addEventListener('click' , function () {
                    const lastChild = container.lastElementChild;
                    lastChild.remove();
                })
            }

            // appel des fonctions

            addInput();
            deleteInput ()
        </script>
    </body>
</html>
