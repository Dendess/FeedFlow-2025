@extends('layouts.app')

@section('content')
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
            <form method="POST" action="{{ route('answer.store') }}">
                @csrf
                @foreach($questions as $index => $question)

                    <input type="hidden" name="answers[{{ $index }}][survey_id]" value="{{ $survey_id }}">
                    <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">
                    <div class="mt-6">
                    <h3 class="font-semibold">{{ $question->title }}</h3>
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

                    @elseif ($question->question_type === 'text')
                            <label class="block">
                                <input type="text" name="answers[{{ $index }}][answer]" required class="border border-black w-full"/>
                            </label>

                    @elseif ($question->question_type === 'scale')
                        <label class="block">
                            <input type="range" name="answers[{{ $index }}][answer]"
                                   min="1" max="10" value="5" class="w-full"
                            >
                            <span class="flex justify-between text-sm text-gray-600">
                                @for ($i = 1; $i <= 10; $i++)
                                    <span>{{ $i }}</span>
                                @endfor
                            </span>
                        </label>
                    @endif
                    </div>
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
@endsection
