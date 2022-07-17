<x-layout>
    <main style="max-width: 800px; margin-inline: auto; padding-block: 4rem; display: flex; flex-direction: column; gap: 1rem;">
        <form>
            <label for="search">Search</label>
            <input id="search" type="text" name="q" value="{{ old('q') }}" placeholder="Taper le nom ou le genre d'un film..." autofocus>

            @error('q')
                <small aria-live="polite">{{ $message }}</small>
            @enderror
        </form>

        <hr>

        <section>
            <ul>
                @foreach ($movies as $movie)
                    <li>
                        <button>
                            <img src="{{ $movie->image }}" width="100" height="100" alt="" aria-hidden="true">
                            
                            <strong>{{ $movie->name }}</strong>
                            <small>{{ $movie->genre }}</small>
                        </button>
                    </li>
                @endforeach
            </ul>
        </section>

        <section>
            {{ $movies->withQueryString()->links() }}
        </section>
    </main>

    <dialog></dialog>

    <script>
        document.querySelectorAll('li > button').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.cloneNode(true);

                const dialog = document.querySelector('dialog');

                dialog.innerHTML = btn.outerHTML;

                dialog.showModal();
            });
        });
    </script>
</x-layout>