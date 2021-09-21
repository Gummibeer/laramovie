<?php

namespace App\Exceptions;

use App\Workflows\Dtos\MovieTransfer;
use RuntimeException;

class TmdbIdMissingException extends RuntimeException
{
    protected ?string $disk = null;
    protected ?string $directory = null;

    public static function movie(MovieTransfer $transfer): self
    {
        $exception = new static(sprintf(
            'TMDB-ID missing for "%s" on "%s".',
            $transfer->directory,
            $transfer->disk
        ));

        return $exception
            ->setDisk($transfer->disk)
            ->setDirectory($transfer->directory);
    }

    public function getDisk(): ?string
    {
        return $this->disk;
    }

    public function setDisk(string $disk): self
    {
        $this->disk = $disk;

        return $this;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }
}
