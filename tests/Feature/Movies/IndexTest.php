<?php

declare(strict_types=1);

namespace Tests\Feature\Movies;

use Tests\TestCase;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function thePageIsSuccessfullyShown(): void
    {
        $this->get(route('movies.index'))->assertStatus(200);
    }

    /**
     * @test
     */
    public function itShowsPaginatedMovieEntriesEvenWhenTheSearchQueryStringIsNotPresent(): void
    {
        $perPage = (new Movie())->getPerPage();

        Movie::factory()->times($perPage * 2)->create();

        $movies = Movie::paginate($perPage);

        $this->get(route('movies.index'))
            ->assertStatus(200)
            ->assertViewHas('movies', $movies)
            ->assertSeeText($movies->pluck('name')->merge($movies->pluck('genre'))->toArray());
    }

    /**
     * @test
     */
    public function itShowsPaginatedMovieEntriesEvenWhenTheSearchQueryStringIsPresentButEmpty(): void
    {
        $perPage = (new Movie())->getPerPage();

        Movie::factory()->times($perPage * 2)->create();

        $movies = Movie::paginate($perPage);

        $this->get(route('movies.index'))
            ->assertStatus(200)
            ->assertViewHas('movies', $movies)
            ->assertSeeText($movies->pluck('name')->merge($movies->pluck('genre'))->toArray());
    }

    /**
     * @test
     */
    public function itRequiresTheSearchQueryStringToBeAtLeast2CharactersLongWhenFilled(): void
    {
        $this->get('/?q=a')
            ->assertStatus(302)
            ->assertRedirect('/');

        $this->get('/?q=ab')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function itUsesTheSearchQueryStringToFilterOutMoviesByNameAndOrGenre(): void
    {
        // $this->withoutExceptionHandling();

        Movie::factory()->create([
            'name' => 'Titanic',
            'genre' => 'Drama',
        ]);

        Movie::factory()->create([
            'name' => 'Titan',
            'genre' => 'Science-Fiction',
        ]);

        Movie::factory()->create([
            'name' => 'Yolo',
            'genre' => 'Unknown',
        ]);

        $this->get('/?q=tit')
            ->assertStatus(200)
            ->assertSeeText(['Titanic', 'Drama', 'Titan', 'Science-Fiction'])
            ->assertDontSeeText(['Yolo', 'Unknown'])
            ->assertViewHas('movies', Movie::searchWithTerm('tit')->paginate()->withQueryString());

        $this->get('/?q=titani')
            ->assertStatus(200)
            ->assertSeeText(['Titanic', 'Drama'])
            ->assertDontSeeText(['Science-Fiction'])
            ->assertViewHas('movies', Movie::searchWithTerm('titani')->paginate()->withQueryString());

        $this->get('/?q=titanic')
            ->assertStatus(200)
            ->assertSeeText(['Titanic', 'Drama'])
            ->assertDontSeeText(['Science-Fiction'])
            ->assertViewHas('movies', Movie::searchWithTerm('titanic')->paginate()->withQueryString());
    }
}
