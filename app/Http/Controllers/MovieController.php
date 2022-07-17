<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\View\View;
use App\Http\Requests\MovieIndexRequest;

class MovieController
{
    public function __invoke(MovieIndexRequest $request): View
    {
        return view('pages.movies.index', [
            'movies' => Movie::search(Movie::searchableFields(), $request->term() ?: '')->paginate(),
        ]);
    }
}
