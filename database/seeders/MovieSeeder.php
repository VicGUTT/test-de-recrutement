<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!config('services.tmdb.key')) {
            Movie::factory((new Movie())->getPerPage() * 5)->create();

            return;
        }

        Artisan::call('data:fetch');

        $basePath = Storage::disk('data')->path('');

        $genres = json_decode(Storage::disk('data')->get('genres/index.json'), true);
        $genres = collect($genres['genres'])->keyBy('id');

        collect(Storage::disk('data')->allFiles('movies'))
            ->map(fn (string $path): string => "{$basePath}/{$path}")
            ->map(fn (string $fullPath): array => json_decode(file_get_contents($fullPath), true))
            ->flatMap(fn (array $content): array => $content['results'])
            ->each(function (array $movie) use ($genres): void {
                $genre = $genres->get($movie['genre_ids'][0]);

                Movie::create([
                    'name' => $movie['title'],
                    'genre' => $genre['name'],
                    'image' => "/movies{$movie['poster_path']}",
                ]);
            });
    }
}
