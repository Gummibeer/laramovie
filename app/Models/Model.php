<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $guarded = false;

    public static function table(): string
    {
        return (new static())->getTable();
    }

    public static function qualifiedColumn(string $column): string
    {
        return (new static())->qualifyColumn($column);
    }
}
