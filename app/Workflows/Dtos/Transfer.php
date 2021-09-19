<?php

namespace App\Workflows\Dtos;

abstract class Transfer
{
    public string $disk;
    public string $directory;
    public ?string $name = null;
    public ?int $year = null;
    public ?int $tmdbId = null;
}
