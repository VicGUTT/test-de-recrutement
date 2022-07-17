<x-layout>
    <header class="container search-form-wrapper">
        <x-search-form :term="$term" />
    </header>

    <main id="search-results" class="container">
        @if ($movies && $movies->isNotEmpty())
            <section class="card-list">
                @foreach ($movies as $movie)
                    <x-movie-card :item="$movie" />
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