<x-layout>
    <header class="container search-form-wrapper">
        <form
            class="search-form"
            o-component="search-form"
            data-error-wrapper-id="#search-error-wrapper"
            data-results-id="#search-results"
        >
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
        </form>

        <div id="search-error-wrapper">
            @error('q')
                <small class="search-error" aria-live="polite">{{ $message }}</small>
            @enderror
        </div>
    </header>

    <main id="search-results" class="container">
        @if ($movies && $movies->isNotEmpty())
            <section class="card-list">
                @foreach ($movies as $movie)
                    <article class="card movie-card">
                        <img
                            class="movie-card-poster"
                            src="{{ $movie->image }}"
                            alt="Image de couverture du film : {{ $movie->name }}"
                            width="180"
                            height="240"
                        >

                        <div class="movie-card-content">
                            <strong class="movie-card-name truncate">{{ $movie->name }}</strong>
                            <small class="movie-card-genre">{{ $movie->genre }}</small>
                        </div>
                        
                        <button class="movie-card-trigger">
                            <span class="sr-only">Plus de détail sur {{ $movie->name }}</span>
                        </button>
                    </article>
                @endforeach
            </section>

            <div class="movie-pagination-wrapper">
                {{ $movies->withQueryString()->links() }}
            </div>
        @else
            <section>
                <p>
                    Il semblerait qu'aucun film n'ait "<strong>{{ $term }}</strong>" comme nom et/ou genre.
                </p>
            </section>
        @endif
    </main>

    <dialog></dialog>

    <div class="decoration-background" aria-hidden="true">
        <div class="container">
            <div style="background: radial-gradient(105.68% 45.69% at 92.95% 50%, rgba(105, 244, 253, 0.5) 0%, rgba(160, 255, 244, 0.094) 53.91%, rgba(254, 216, 255, 0) 100%), radial-gradient(103.18% 103.18% at 90.11% 102.39%, rgb(201, 255, 242) 0%, rgba(230, 255, 250, 0) 100%), radial-gradient(90.45% 90.45% at 87.84% 9.55%, rgb(255, 210, 245) 0%, rgba(254, 219, 246, 0) 100%), linear-gradient(135.66deg, rgba(203, 185, 255, 0.8) 14.89%, rgba(216, 202, 254, 0) 74.33%); background-blend-mode: normal, normal, normal, normal, normal, normal; filter: blur(200px); border-radius: 15rem;"></div>
        </div>
    </div>
</x-layout>