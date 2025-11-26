<x-guest-layout>

    <form method="POST" action="{{ route('answer.store') }}">
        @csrf
        @foreach($questions as $index => $question)
            {{ $question->title }}
            <!-- Answer field -->
            <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">
            <div>
                <x-input-label for="answer" />
                <x-text-input id="answers" class="block mt-1 w-full" type="text"
                              name="answers[{{ $index }}][answer]" required />
            </div>

        @endforeach

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                Répondre
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
