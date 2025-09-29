<?php

namespace App\Enums;

enum FileSizeUnit: string
{
    case B = 'B';
    case KB = 'KB';
    case MB = 'MB';
    case GB = 'GB';
    case TB = 'TB';
    case PB = 'PB';
    case KiB = 'KiB';
    case MiB = 'MiB';
    case GiB = 'GiB';
    case TiB = 'TiB';
    case PiB = 'PiB';

    public static function binaries(): array
    {
        return [
            self::B,
            self::KiB,
            self::MiB,
            self::GiB,
            self::TiB,
            self::PiB,
        ];
    }

    public static function decimals(): array
    {
        return [
            self::B,
            self::KB,
            self::MB,
            self::GB,
            self::TB,
            self::PB,
        ];
    }

    public function isBinary(): bool
    {
        return str_contains($this->value, 'i');
    }

    public function getFactor(): int
    {
        return match ($this) {
            self::B => 0,
            self::KiB, self::KB => 1,
            self::MiB, self::MB => 2,
            self::GiB, self::GB => 3,
            self::TiB, self::TB => 4,
            self::PiB, self::PB => 5,
        };
    }

    public function getBase(): int
    {
        if ($this === self::B) {
            return 0;
        }

        return $this->isBinary() ? 1024 : 1000;
    }

    public function getMultiplier(): int
    {
        return $this->getBase() ** $this->getFactor();
    }
}
