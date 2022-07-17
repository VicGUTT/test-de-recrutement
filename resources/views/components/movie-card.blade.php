<article class="card movie-card">
    <img
        class="movie-card-poster"
        src="{{ $item->image }}"
        alt="Image de couverture du film : {{ $item->name }}"
        width="180"
        height="240"
    >

    <div class="movie-card-content">
        <strong class="movie-card-name truncate">{{ $item->name }}</strong>
        <small class="movie-card-genre">{{ $item->genre }}</small>
    </div>
    
    <button class="movie-card-trigger">
        <span class="sr-only">Plus de détail sur {{ $item->name }}</span>
    </button>
</article>