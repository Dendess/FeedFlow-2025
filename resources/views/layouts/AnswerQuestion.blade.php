<x-guest-layout>
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

</x-guest-layout>
