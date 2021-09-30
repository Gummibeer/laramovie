<?php

namespace App\SourceProviders\Contracts;

use Illuminate\Support\Collection;

interface Source
{
    public function movies(): Collection;

    public function url(string $id): string;
}
