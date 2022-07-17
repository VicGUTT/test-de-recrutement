<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $genre
 * @property string $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;


    /* Scopes
    ------------------------------------------------*/

    /**
     * @example Movie::search(['name', 'genre'], 'Titanic')->get();
     */
    public function scopeSearch(Builder $query, array $attributes, string $term): Builder
    {
        return $query->where(function (Builder $query) use ($attributes, $term) {
            foreach ($attributes as $attribute) {
                $query->orWhere($attribute, 'LIKE', "%{$term}%");
            }
        });
    }


    /* Methods
    ------------------------------------------------*/

    public static function searchableFields(): array
    {
        return ['name', 'genre'];
    }
}
