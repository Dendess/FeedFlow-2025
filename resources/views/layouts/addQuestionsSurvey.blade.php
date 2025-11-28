<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Ajouter une question') }}</h2>
    </x-slot>

    <style>
        .select-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .select-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .step-indicator {
            transition: all 0.2s;
        }
        .step-indicator.active {
            background-color: #4f46e5;
            color: white;
        }
        .step-indicator.completed {
            background-color: #10b981;
            color: white;
        }
        .step-indicator.pending {
            background-color: #e5e7eb;
            color: #6b7280;
        }
        .dropdown-select {
            transition: border-color 0.2s;
        }
        .dropdown-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
    </style>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
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

            <!-- Progress Steps -->
            <div class="mb-6 flex items-center justify-center gap-3">
                <div class="flex items-center gap-2">
                    <div id="step1-indicator" class="step-indicator w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold active">1</div>
                    <span class="text-xs font-medium text-gray-700">Organisation</span>
                </div>
                <div class="w-8 h-0.5 bg-gray-300" id="line1"></div>
                <div class="flex items-center gap-2">
                    <div id="step2-indicator" class="step-indicator w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold pending">2</div>
                    <span class="text-xs font-medium text-gray-700">Sondage</span>
                </div>
                <div class="w-8 h-0.5 bg-gray-300" id="line2"></div>
                <div class="flex items-center gap-2">
                    <div id="step3-indicator" class="step-indicator w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold pending">3</div>
                    <span class="text-xs font-medium text-gray-700">Question</span>
                </div>
            </div>

            <!-- Step 1: Organization Selection -->
            <section class="bg-white rounded-lg shadow border border-gray-200 mb-4">
                <div class="bg-indigo-600 text-white px-4 py-3 rounded-t-lg">
                    <h3 class="text-sm font-semibold">Étape 1 : Sélectionnez votre organisation</h3>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <label for="organization-select" class="block text-xs font-medium text-gray-700 mb-1.5">
                            Organisation <span class="text-red-500">*</span>
                        </label>
                        <select id="organization-select" class="dropdown-select w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="">-- Sélectionnez une organisation --</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1.5">Seules vos organisations admin sont listées</p>
                    </div>
                    <div id="org-message" class="text-xs mt-2 hidden"></div>
                </div>
            </section>

            <!-- Step 2: Survey Selection -->
            <section id="survey-section" class="bg-white rounded-lg shadow border border-gray-200 mb-4 opacity-50 pointer-events-none">
                <div class="bg-purple-600 text-white px-4 py-3 rounded-t-lg">
                    <h3 class="text-sm font-semibold">Étape 2 : Sélectionnez le sondage</h3>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <label for="survey-select" class="block text-xs font-medium text-gray-700 mb-1.5">
                            Sondage <span class="text-red-500">*</span>
                        </label>
                        <select id="survey-select" class="dropdown-select w-full border border-gray-300 rounded px-3 py-2 text-sm" disabled>
                            <option value="">-- Sélectionnez d'abord une organisation --</option>
                        </select>
                    </div>
                    <div id="survey-message" class="text-xs mt-2 hidden"></div>
                </div>
            </section>

            <!-- Step 3: Question Form -->
            <section id="question-section" class="bg-white rounded-lg shadow border border-gray-200 mb-4 opacity-50 pointer-events-none">
                <div class="bg-green-600 text-white px-4 py-3 rounded-t-lg">
                    <h3 class="text-sm font-semibold">Étape 3 : Rédigez votre question</h3>
                </div>
                <div class="p-4">
                    <form action="{{ route('surveys.questions.store') }}" method="POST" id="question-form" class="space-y-4">
                        @csrf
                        <input type="hidden" name="survey_id" id="survey_id_hidden" value="{{ old('survey_id', request('survey')) }}">
                        <input type="hidden" id="organization_id_hidden" value="{{ request('organization') }}">
                        <input type="hidden" name="question_type_final" id="question_type_final" value="text">

                        <div id="selected-context" class="flex items-center gap-2 text-xs bg-indigo-50 border-l-4 border-indigo-500 px-3 py-2 rounded hidden">
                            <span class="font-medium">Contexte:</span>
                            <span id="context-text" class="text-gray-700"></span>
                        </div>

                        <div>
                            <label for="title" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Titre de la question <span class="text-red-500">*</span>
                            </label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}" required 
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                   placeholder="Ex: Êtes-vous satisfait de nos services ?">
                        </div>

                        <fieldset class="border border-gray-200 rounded p-3">
                            <legend class="text-xs font-medium text-gray-700 px-2">Type de question</legend>
                            <div class="mt-2 space-y-2">
                                <label class="flex items-center gap-2 p-2 border border-gray-300 rounded cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="question_type" value="text" class="question-type-radio w-4 h-4 text-indigo-600" checked>
                                    <span class="text-sm">Texte libre</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 border border-gray-300 rounded cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="question_type" value="scale" class="question-type-radio w-4 h-4 text-indigo-600">
                                    <span class="text-sm">Échelle (1-10)</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 border border-gray-300 rounded cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="question_type" value="option_single" class="question-type-radio w-4 h-4 text-indigo-600" id="option-radio">
                                    <span class="text-sm">Choix</span>
                                </label>
                            </div>
                        </fieldset>

                        <div id="scale-container" class="hidden border border-gray-200 rounded p-3">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Configuration de l'échelle</label>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-gray-600 mb-1 block">Min</label>
                                    <input type="number" name="scale_min" id="scale_min" value="1" min="1" 
                                           class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 mb-1 block">Max</label>
                                    <input type="number" name="scale_max" id="scale_max" value="10" min="1" 
                                           class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 mb-1 block">Aperçu</label>
                                    <input type="range" id="scale" name="scale" min="1" max="10" value="5" 
                                           class="w-full" oninput="document.getElementById('scaleValue').textContent = scale.value">
                                    <div class="text-center text-xs font-semibold text-indigo-600 mt-1" id="scaleValue">5</div>
                                </div>
                            </div>
                        </div>

                        <div id="options-container" class="hidden border border-gray-200 rounded p-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Options de réponse</label>
                            <p class="text-xs text-gray-500 mb-2">Ajoutez au moins 2 options</p>
                            <div id="options-list" class="space-y-2"></div>
                            <button type="button" id="add-option" 
                                    class="mt-2 text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-medium py-1.5 px-3 rounded transition">
                                ➕ Ajouter une option
                            </button>
                            <label class="flex items-center gap-2 mt-3 text-xs text-gray-700 cursor-pointer">
                                <input type="checkbox" id="allow_multiple_checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600">
                                <span>Autoriser plusieurs réponses (choix multiples)</span>
                            </label>
                        </div>

                        <div id="survey-guard" class="flex items-center gap-2 text-xs text-orange-700 bg-orange-50 border-l-4 border-orange-500 px-3 py-2 rounded">
                            <span>⚠️</span>
                            <span>Sélectionnez d'abord un sondage pour activer l'enregistrement.</span>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <a href="{{ route('surveys.index') }}" 
                               class="text-sm text-gray-600 hover:text-indigo-600 transition">
                                Annuler
                            </a>
                            <button type="submit" id="question-submit" disabled
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-5 rounded transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md">
                                Enregistrer la question
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const typeRadios = document.querySelectorAll('.question-type-radio');
            const scaleContainer = document.getElementById('scale-container');
            const optionsContainer = document.getElementById('options-container');
            const optionsList = document.getElementById('options-list');
            const addOptionBtn = document.getElementById('add-option');
            
            const orgSelect = document.getElementById('organization-select');
            const surveySelect = document.getElementById('survey-select');
            const orgMessageEl = document.getElementById('org-message');
            const surveyMessageEl = document.getElementById('survey-message');
            
            const surveySection = document.getElementById('survey-section');
            const questionSection = document.getElementById('question-section');
            
            const surveyIdHidden = document.getElementById('survey_id_hidden');
            const organizationIdHidden = document.getElementById('organization_id_hidden');
            const selectedContext = document.getElementById('selected-context');
            const contextText = document.getElementById('context-text');
            const surveyGuard = document.getElementById('survey-guard');
            const submitBtn = document.getElementById('question-submit');

            // Step indicators
            const step1Indicator = document.getElementById('step1-indicator');
            const step2Indicator = document.getElementById('step2-indicator');
            const step3Indicator = document.getElementById('step3-indicator');
            const line1 = document.getElementById('line1');
            const line2 = document.getElementById('line2');

            let selectedOrganization = null;
            let selectedSurvey = null;

            // URL params (for direct links from survey list)
            const urlParams = new URLSearchParams(window.location.search);
            const preselectedSurveyId = urlParams.get('survey') || '{{ request("survey") }}';
            const preselectedOrgId = '{{ request("organization") }}' || organizationIdHidden.value;

            // Helper functions
            function updateStepIndicators(step) {
                [step1Indicator, step2Indicator, step3Indicator].forEach(el => {
                    el.classList.remove('active', 'completed', 'pending');
                    el.classList.add('pending');
                });
                [line1, line2].forEach(el => el.classList.remove('bg-green-500'));

                if(step >= 1) {
                    step1Indicator.classList.remove('pending');
                    step1Indicator.classList.add('completed');
                    line1.classList.add('bg-green-500');
                }
                if(step >= 2) {
                    step2Indicator.classList.remove('pending');
                    step2Indicator.classList.add('completed');
                    line2.classList.add('bg-green-500');
                }
                if(step >= 3) {
                    step3Indicator.classList.remove('pending');
                    step3Indicator.classList.add('active');
                }
                
                if(step === 1) step1Indicator.classList.add('active');
                if(step === 2) step2Indicator.classList.add('active');
            }

            function enableSection(section, enable = true) {
                if(enable) {
                    section.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    section.classList.add('opacity-50', 'pointer-events-none');
                }
            }

            function setMessage(element, text, type = 'info') {
                if(!element) return;
                if(!text) {
                    element.classList.add('hidden');
                    element.textContent = '';
                    return;
                }
                const colors = {
                    error: 'text-red-600 bg-red-50 border-l-4 border-red-500',
                    success: 'text-green-600 bg-green-50 border-l-4 border-green-500',
                    info: 'text-blue-600 bg-blue-50 border-l-4 border-blue-500'
                };
                element.className = `text-sm px-4 py-2 rounded ${colors[type] || colors.info}`;
                element.textContent = text;
                element.classList.remove('hidden');
            }

            function setVisibility(type){
                // Cacher tous les conteneurs
                scaleContainer.classList.add('hidden');
                optionsContainer.classList.add('hidden');
                
                // Retirer required de tous les champs cachés
                scaleContainer.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
                optionsContainer.querySelectorAll('input[name="options[]"]').forEach(input => input.removeAttribute('required'));
                
                // Afficher et activer required pour le type sélectionné
                if(type === 'scale') {
                    scaleContainer.classList.remove('hidden');
                } else if(type === 'option_single' || type === 'option_multiple') {
                    optionsContainer.classList.remove('hidden');
                    
                    // Ajouter 2 options par défaut si la liste est vide
                    if(optionsList.children.length === 0) {
                        addOption();
                        addOption();
                    }
                    
                    // Activer required sur les options visibles
                    optionsContainer.querySelectorAll('input[name="options[]"]').forEach(input => input.setAttribute('required', 'required'));
                }
            }

            function addOption(value = ''){
                const idx = optionsList.children.length;
                const wrapper = document.createElement('div');
                wrapper.className = 'flex gap-2 items-center';
                
                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'options[]';
                input.value = value;
                input.className = 'flex-1 border-2 border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500';
                input.placeholder = `Option ${idx+1}`;
                
                // Ajouter required seulement si le conteneur d'options est visible
                if(!optionsContainer.classList.contains('hidden')) {
                    input.setAttribute('required', 'required');
                }
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'remove-option bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition-all';
                removeBtn.textContent = 'Supprimer';
                removeBtn.addEventListener('click', ()=>{
                    wrapper.remove();
                    normalizeOptionPlaceholders();
                });
                
                wrapper.appendChild(input);
                wrapper.appendChild(removeBtn);
                optionsList.appendChild(wrapper);
            }

            function normalizeOptionPlaceholders(){
                Array.from(optionsList.children).forEach((child, i)=>{
                    const input = child.querySelector('input[name="options[]"]');
                    if(input) input.placeholder = `Option ${i+1}`;
                });
            }

            function updateSubmitState(){
                const canSubmit = selectedSurvey !== null;
                submitBtn.disabled = !canSubmit;
                if(canSubmit) {
                    surveyGuard.classList.add('hidden');
                    enableSection(questionSection, true);
                    updateStepIndicators(3);
                } else {
                    surveyGuard.classList.remove('hidden');
                    enableSection(questionSection, false);
                }
            }

            function renderContext(){
                if(!selectedOrganization) {
                    selectedContext.classList.add('hidden');
                    return;
                }
                const surveyInfo = selectedSurvey ? ` - ${selectedSurvey.title}` : '';
                contextText.textContent = `${selectedOrganization.name}${surveyInfo}`;
                selectedContext.classList.remove('hidden');
            }

            // Load organizations on page load
            async function loadOrganizations() {
                console.log('Loading organizations...');
                setMessage(orgMessageEl, 'Chargement des organisations...', 'info');
                
                try {
                    const url = '{{ route("my.organizations.search") }}?q=';
                    console.log('Fetching:', url);
                    
                    const res = await fetch(url, {
                        method: 'GET',
                        headers: { 
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        credentials: 'same-origin'
                    });
                    
                    console.log('Response status:', res.status);
                    console.log('Response headers:', Object.fromEntries(res.headers));
                    
                    if(!res.ok) {
                        const errorText = await res.text();
                        console.error('Server error:', errorText);
                        setMessage(orgMessageEl, `Erreur ${res.status}: Impossible de charger vos organisations.`, 'error');
                        return;
                    }
                    
                    const orgs = await res.json();
                    console.log('Organisations trouvées:', orgs);
                    
                    orgSelect.innerHTML = '<option value="">-- Sélectionnez une organisation --</option>';
                    
                    if(!orgs || orgs.length === 0) {
                        setMessage(orgMessageEl, 'Aucune organisation où vous êtes admin.', 'error');
                        return;
                    }
                    
                    orgs.forEach(org => {
                        const option = document.createElement('option');
                        option.value = org.id;
                        option.textContent = org.name;
                        option.dataset.orgName = org.name;
                        orgSelect.appendChild(option);
                    });
                    
                    setMessage(orgMessageEl, `${orgs.length} organisation(s) chargée(s)`, 'success');
                    
                    // Handle pre-selection: if survey is provided, find its organization
                    if(preselectedSurveyId && !preselectedOrgId) {
                        await loadOrganizationForSurvey(preselectedSurveyId);
                    } else if(preselectedOrgId) {
                        orgSelect.value = preselectedOrgId;
                        const event = new Event('change');
                        orgSelect.dispatchEvent(event);
                    }
                } catch(err) {
                    console.error('Error loading organizations', err);
                    setMessage(orgMessageEl, 'Erreur lors du chargement des organisations: ' + err.message, 'error');
                }
            }

            // Find and select organization for a given survey
            async function loadOrganizationForSurvey(surveyId) {
                try {
                    // Try each organization to find which one contains this survey
                    const orgOptions = Array.from(orgSelect.querySelectorAll('option[value]')).filter(opt => opt.value);
                    for (const opt of orgOptions) {
                        const res = await fetch(`/organizations/${opt.value}/surveys/json`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if(res.ok) {
                            const surveys = await res.json();
                            const found = surveys.find(s => s.id == surveyId);
                            if(found) {
                                orgSelect.value = opt.value;
                                const event = new Event('change');
                                orgSelect.dispatchEvent(event);
                                return;
                            }
                        }
                    }
                } catch(err) {
                    console.error('Error finding organization for survey', err);
                }
            }

            // Load surveys for selected organization
            async function loadSurveysForOrg(orgId){
                if(!orgId) return;
                enableSection(surveySection, false);
                setMessage(surveyMessageEl, 'Chargement des sondages...', 'info');
                surveySelect.innerHTML = '<option value="">Chargement...</option>';
                surveySelect.disabled = true;
                
                try{
                    const res = await fetch(`/organizations/${encodeURIComponent(orgId)}/surveys/json`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if(!res.ok) throw new Error('Failed to load');
                    const surveys = await res.json();
                    
                    surveySelect.innerHTML = '<option value="">-- Sélectionnez un sondage --</option>';
                    if(surveys.length === 0) {
                        setMessage(surveyMessageEl, 'Aucun sondage disponible pour cette organisation.', 'error');
                        surveySelect.disabled = true;
                        return;
                    }
                    
                    surveys.forEach(s => {
                        const option = document.createElement('option');
                        option.value = s.id;
                        option.textContent = s.title;
                        option.dataset.surveyTitle = s.title;
                        surveySelect.appendChild(option);
                    });
                    
                    surveySelect.disabled = false;
                    enableSection(surveySection, true);
                    updateStepIndicators(2);
                    setMessage(surveyMessageEl, `${surveys.length} sondage(s) disponible(s)`, 'success');
                    
                    // Pre-select if URL param exists
                    if(preselectedSurveyId) {
                        surveySelect.value = preselectedSurveyId;
                        const event = new Event('change');
                        surveySelect.dispatchEvent(event);
                    }
                }catch(err){
                    console.error('Error loading surveys', err);
                    setMessage(surveyMessageEl, 'Erreur lors du chargement des sondages.', 'error');
                    surveySelect.disabled = true;
                }
            }

            // Event: Organization selected
            orgSelect.addEventListener('change', (e) => {
                const orgId = e.target.value;
                const orgName = e.target.options[e.target.selectedIndex]?.dataset.orgName;
                
                if(!orgId) {
                    selectedOrganization = null;
                    selectedSurvey = null;
                    surveySelect.innerHTML = '<option value="">-- Sélectionnez d\'abord une organisation --</option>';
                    surveySelect.disabled = true;
                    enableSection(surveySection, false);
                    enableSection(questionSection, false);
                    updateStepIndicators(1);
                    renderContext();
                    updateSubmitState();
                    return;
                }
                
                selectedOrganization = { id: orgId, name: orgName };
                organizationIdHidden.value = orgId;
                selectedSurvey = null;
                surveyIdHidden.value = '';
                updateStepIndicators(1);
                renderContext();
                updateSubmitState();
                loadSurveysForOrg(orgId);
            });

            // Event: Survey selected
            surveySelect.addEventListener('change', (e) => {
                const surveyId = e.target.value;
                const surveyTitle = e.target.options[e.target.selectedIndex]?.dataset.surveyTitle;
                
                if(!surveyId) {
                    selectedSurvey = null;
                    surveyIdHidden.value = '';
                    enableSection(questionSection, false);
                    renderContext();
                    updateSubmitState();
                    return;
                }
                
                selectedSurvey = { id: surveyId, title: surveyTitle };
                surveyIdHidden.value = surveyId;
                setMessage(surveyMessageEl, `Sondage sélectionné: ${surveyTitle}`, 'success');
                renderContext();
                updateSubmitState();
            });

            // Question type radio buttons
            typeRadios.forEach(r => r.addEventListener('change', (e)=> {
                setVisibility(e.target.value);
                // Mettre à jour le champ hidden avec le bon type
                updateQuestionTypeFinal();
            }));

            // Add option button
            addOptionBtn.addEventListener('click', ()=> addOption());

            // Checkbox pour autoriser plusieurs réponses
            const allowMultipleCheckbox = document.getElementById('allow_multiple_checkbox');
            const questionTypeFinalInput = document.getElementById('question_type_final');
            
            if (allowMultipleCheckbox) {
                allowMultipleCheckbox.addEventListener('change', updateQuestionTypeFinal);
            }

            function updateQuestionTypeFinal() {
                const selectedType = document.querySelector('input[name="question_type"]:checked')?.value || 'text';
                
                if (selectedType === 'option_single') {
                    // Si c'est un type choix, vérifier la checkbox
                    if (allowMultipleCheckbox && allowMultipleCheckbox.checked) {
                        questionTypeFinalInput.value = 'option_multiple';
                    } else {
                        questionTypeFinalInput.value = 'option_single';
                    }
                } else {
                    // Pour les autres types, garder tel quel
                    questionTypeFinalInput.value = selectedType;
                }
                
                console.log('Type de question final:', questionTypeFinalInput.value);
            }

            // Initialize the page
            loadOrganizations();
            setVisibility('text');
            updateSubmitState();
            updateQuestionTypeFinal();
        });
    </script>
@endpush
</x-app-layout>
