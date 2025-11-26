@props(['organization' => null, 'action', 'method' => 'POST', 'submit' => 'Enregistrer'])

<form action="{{ $action }}" method="POST" class="org-form">
    @csrf
    @if(strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="mb-4">
        <label for="name">Nom</label>
        <input
            id="name"
            name="name"
            type="text"
            required
            value="{{ old('name', $organization->name ?? '') }}"
            class="org-input"
        />
        @error('name')
            <p class="org-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="org-form-actions">
        <a href="{{ route('organizations.index') }}" class="org-btn-secondary">Annuler</a>
        <button type="submit" class="org-btn-primary">{{ $submit }}</button>
    </div>
</form>
