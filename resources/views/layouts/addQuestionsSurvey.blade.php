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
                    <div class="answers-options" id="options-container" hidden>
                        <input type="text" id="options" name="options[]" placeholder="Option de réponse">
                    </div>
                    <br>
                    <br>
                    <nav id="question-type-menu" class="mt-4">
                        <fieldset>
                            <legend class="font-semibold mb-2">Type de question</legend>

                            <label class="block mb-1">
                                <input
                                    type="radio"
                                    name="question_type"
                                    id="question-type-text"
                                    value="text"
                                    class="question-type-radio"
                                    data-question-type="text"
                                    checked
                                >
                                Texte
                            </label>

                            <label class="block mb-1">
                                <input
                                    type="radio"
                                    name="question_type"
                                    id="question-type-scale"
                                    value="scale"
                                    class="question-type-radio"
                                    data-question-type="scale"
                                >
                                Échelle (1–10)
                            </label>

                            <label class="block mb-1">
                                <input
                                    type="radio"
                                    name="question_type"
                                    id="question-type-option"
                                    value="option"
                                    class="question-type-radio"
                                    data-question-type="option"
                                >
                                Option de réponse
                            </label>
                        </fieldset>
                    </nav>
                    <br>
                    <br>
                    <div id="scale-container" hidden>
                    <input type="range" id="scale" name="scale" min="1" max="10" value="5" oninput="scaleValue.value = scale.value">
                    <output id="scaleValue">5</output>
                    </div>
                    <br>
                    <br>
                    <div id="checkbox-nb-ansewer-container" hidden>
                        <input type="checkbox" id="question_type_multi_or_single" name="question_type_multi_or_single">Plusieurs réponses possibles</input>
                    </div>
                    <br>
                    <br>
                    <button type="submit"
                        class="mt-4 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                        Validée
                    </button>
                </form>
            </div>
        </div>

        <script>

            // pour le nombre d'option
            const slider = document.getElementById('scale');
            const scaleValue = document.getElementById('scaleValue');
            const container = document.getElementById('options-container');

            function generateOptions() {
                const n = parseInt(slider.value);

                container.innerHTML = '';

                for (let i = 0; i < n; i++) {
                    const newInput = document.createElement('input');
                    newInput.type = 'text';
                    newInput.name = 'options[]';
                    newInput.placeholder = 'Option de réponse';
                    newInput.classList.add('mb-2');
                    container.appendChild(newInput);
                }
            }

            generateOptions();

            slider.addEventListener('input', () => {
                scaleValue.value = slider.value;
                generateOptions();
            });

            // pour gérer les élément visible et utilisable

            const scaleContainer = document.getElementById('scale-container');
            const checkboxNbAnsewer = document.getElementById('checkbox-nb-ansewer-container')
            const typeRadios = document.querySelectorAll('.question-type-radio');

            // affichage en fonction du type de question sélectionné
            function updateQuestionTypeUI() {
                const selected = document.querySelector('.question-type-radio:checked');
                if (!selected) return;

                const type = selected.dataset.questionType; // "text" | "scale" | "options"

                if (type === 'option') {
                    container.hidden = false;
                    scaleContainer.hidden = false;
                    checkboxNbAnsewer.hidden = false;
                } else {
                    container.hidden = true;
                    scaleContainer.hidden = true;
                    checkboxNbAnsewer.hidden = true;

                }
            }

            // listener sur les radios
            typeRadios.forEach((radio) => {
                radio.addEventListener('change', updateQuestionTypeUI);
            });

            // initialisation
            updateQuestionTypeUI();
            slider.addEventListener('input', () => {
                scaleValue.value = slider.value;
                generateOptions();
            });
        </script>
    </body>
</html>
