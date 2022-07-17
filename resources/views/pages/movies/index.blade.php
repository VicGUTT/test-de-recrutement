<x-layout>
    <form action="">
        <label for="search">Search</label>
        <input id="search" type="text" name="q" value="{{ old('q') }}" placeholder="Taper le nom ou le genre d'un film..." autofocus>

        @error('q')
            <small aria-live="polite">{{ $message }}</small>
        @enderror
    </form>

    <hr>

    <ul>
        @foreach ($movies as $movie)
            <li>
                {{ $movie->name }} | {{ $movie->genre }}
            </li>
        @endforeach
    </ul>
</x-layout>