<?php

namespace App\Helpers;

use App\Enums\FileSizeUnit;
use InvalidArgumentException;

final class FileSize
{
    private int $bytes;

    private int $decimals = 2;

    public function __construct(int $bytes)
    {
        $this->bytes = $bytes;
    }

    public static function parse(string|int $size): self
    {
        if (is_int($size)) {
            return new self($size);
        }

        if (preg_match('/^([\d.]+)\s*([KMGTPE]?i?B)$/i', trim($size), $matches)) {
            $value = (float) $matches[1];
            $unit = FileSizeUnit::from($matches[2]);

            $bytes = (int) round($value * $unit->getMultiplier());

            return new self($bytes);
        }

        throw new InvalidArgumentException("Invalid size format [{$size}].");
    }

    public function setDecimals(int $decimals): self
    {
        $this->decimals = max(0, $decimals);

        return $this;
    }

    public function toUnit(FileSizeUnit $unit): float
    {
        return $this->bytes / $unit->getMultiplier();
    }

    public function forHuman(bool $binary = false): string
    {
        $units = $binary ? FileSizeUnit::binaries() : FileSizeUnit::decimals();

        $value = $this->bytes;
        $unitIndex = 0;

        foreach ($units as $index => $unit) {
            if ($value < ($unit->getBase() / 2) || $index === count($units) - 1) {
                break;
            }

            if ($unit->getBase() > 0) {
                $value /= $unit->getBase();
            }
            $unitIndex = $index;
        }

        $unit = $units[$unitIndex];

        return number_format($value, $this->decimals).' '.$unit->value;
    }

    public function toBytes(): int
    {
        return $this->bytes;
    }
}
