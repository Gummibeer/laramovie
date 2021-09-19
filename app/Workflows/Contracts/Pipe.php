<?php

namespace App\Workflows\Contracts;

use Closure;

interface Pipe
{
    public function handle($payload, Closure $next): mixed;
}
