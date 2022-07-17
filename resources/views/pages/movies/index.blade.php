<x-layout>
    <ul>
        @foreach ($movies as $movie)
            <li>
                {{ $movie->name }} | {{ $movie->genre }}
            </li>
        @endforeach
    </ul>
</x-layout>