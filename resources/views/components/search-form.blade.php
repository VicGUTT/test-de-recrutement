<form
    class="search-form"
    o-component="search-form"
    data-error-wrapper-id="#search-error-wrapper"
    data-results-id="#search-results"
>
    <div class="search-form-content">
        <input
            id="search"
            class="search-field"
            type="text"
            name="q"
            placeholder="Taper le nom ou le genre d'un film..."
            value="{{ old('q', $term) }}"
            autofocus
        >
        <label class="sr-only" for="search">Rechercher un film par son nom ou son genre</label>

        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>

    <x-loader />
</form>

<div id="search-error-wrapper">
    @error('q')
        <small class="search-error" aria-live="polite">{{ $message }}</small>
    @enderror
</div>