<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\View\View;

class MovieController
{
    public function __invoke(): View
    {
        return view('pages.movies.index', [
            'movies' => Movie::all(),
        ]);
    }
}
