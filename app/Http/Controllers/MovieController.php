<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\View\View;
use App\Http\Requests\MovieIndexRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieController
{
    public function __invoke(MovieIndexRequest $request): View
    {
        $term = $request->term();

        return view('pages.movies.index', [
            'movies' => Movie::query()->when(
                $term,
                fn (Builder $query): LengthAwarePaginator => $query->searchWithTerm($term)->paginate(),
                fn (Builder $query): LengthAwarePaginator => $query->paginate(),
            ),
            'term' => $term,
        ]);
    }
}
