<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $server_id
 * @property string $user
 * @property int $file_id
 * @property string $file_name
 * @property int $bytes
 * @property string $mimetype
 * @property string|null $resolution
 * @property int|null $year
 * @property string|null $query
 * @property string|null $locale
 * @property int|null $season
 * @property int|null $episode
 * @property string $offered_at
 * @property string|null $tmdb_type
 * @property int|null $tmdb_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class FileOffer extends Model
{
    protected $connection = 'exdcc';

    protected $casts = [
        'offered_at' => 'datetime',
    ];
}
