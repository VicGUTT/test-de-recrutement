<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class FetchStaticImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch static movie images from TMDB.';

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
        if (!$this->shouldFetchImages()) {
            $this->info("\nThe images have already been fetched !\n");

            return 0;
        }

        $this->info("\nFetching the necessary images...\n");

        $this->fetchImages();

        $this->info("\n\nDone !\n");

        return 0;
    }

    private function shouldFetchImages(): bool
    {
        return !file_exists($this->imageDisk()->path('movies'));
    }

    private function fetchImages(): void
    {
        $paths = collect($this->dataDisk()->allFiles('movies'))
            ->map(fn (string $path): string => $this->dataDisk()->path($path))
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
        $disk = $this->imageDisk();

        if ($disk->exists($path)) {
            return;
        }

        $disk->put($path, fopen($url, 'r'));
    }

    private function imageDisk(): FilesystemAdapter
    {
        return Storage::disk('public');
    }

    private function dataDisk(): FilesystemAdapter
    {
        return Storage::disk('data');
    }
}
