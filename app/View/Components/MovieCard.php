<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Movie;
use Illuminate\View\Component;

class MovieCard extends Component
{
    public Movie $item;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Movie $item)
    {
        $this->item = $item;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.movie-card');
    }
}
