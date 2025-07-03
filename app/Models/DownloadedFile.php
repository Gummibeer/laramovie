<?php

namespace App\Models;

/**
 * @property string $file_name
 */
class DownloadedFile extends Model
{
    protected $connection = 'exdcc';

    protected $primaryKey = 'file_name';

    public $incrementing = false;
}
