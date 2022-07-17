<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class SearchForm extends Component
{
    public ?string $term;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(?string $term)
    {
        $this->term = $term;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.search-form');
    }
}
