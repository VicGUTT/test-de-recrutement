<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class FetchStaticDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch static movie related data from TMDB.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->shouldFetchData()) {
            $this->info("\nThe static data have already been fetched !\n");

            return 0;
        }

        if (!config('services.tmdb.key')) {
            $this->error("\nMissing TMDB API key ! Please provide a valid `TMDB_KEY` environement key.\n");

            return 1;
        }

        $this->fetchData();

        return 0;
    }

    private function shouldFetchData(): bool
    {
        return !file_exists($this->basePath('movies')) || !file_exists($this->basePath('genres'));
    }

    private function fetchData(): void
    {
        $key = config('services.tmdb.key');

        File::ensureDirectoryExists($this->basePath('movies'));
        File::ensureDirectoryExists($this->basePath('genres'));

        $this->info("\nFetching static data...\n");

        for ($page = 1; $page < 4; $page++) {
            $movies = $this->fetch("https://api.themoviedb.org/3/movie/now_playing?api_key={$key}&page={$page}");

            $this->saveData("{$this->basePath('movies')}/page_{$page}.json", $movies);
        }

        $genres = $this->fetch("https://api.themoviedb.org/3/genre/movie/list?api_key={$key}");

        $this->saveData("{$this->basePath('genres')}/index.json", $genres);

        $this->info("\nFetching the necessary images...\n");

        $this->handleImages();

        $this->info("\nDone !\n");
    }

    private function handleImages(): void
    {
        $paths = collect($this->disk()->allFiles('movies'))
            ->map(fn (string $path): string => "{$this->basePath('')}/{$path}")
            ->map(fn (string $fullPath): array => json_decode(file_get_contents($fullPath), true))
            ->flatMap(fn (array $content): Collection => collect($content['results'])->pluck('poster_path'));

        $this->withProgressBar($paths, function (string $path) {
            $this->fetchAndSaveImage("https://image.tmdb.org/t/p/w500{$path}");
        });
    }

    private function fetchAndSaveImage(string $url): void
    {
        $fileName = pathinfo($url, PATHINFO_BASENAME);
        $path = "movies/{$fileName}";
        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            return;
        }

        $disk->put($path, fopen($url, 'r'));
    }

    private function disk(): FilesystemAdapter
    {
        return Storage::disk('data');
    }

    private function basePath(string $path = ''): string
    {
        return $this->disk()->path($path);
    }

    private function fetch(string $url): array
    {
        return Http::get($url)
                ->throw()
                ->json();
    }

    private function saveData(string $path, array $data): void
    {
        file_put_contents($path, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
