<?php

namespace App\Nova;

use App\Nova\Concerns\ReadonlyResource;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class FileOffer extends Resource
{
    use ReadonlyResource;

    public static $model = \App\Models\FileOffer::class;

    public static $title = 'file_name';

    public static $search = [
        'file_name', 'user', 'mimetype',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            Number::make('Server ID', 'server_id')->sortable(),
            Text::make('User')->sortable(),
            Number::make('File ID', 'file_id')->sortable(),
            Text::make('File Name', 'file_name')->sortable(),
            Number::make('Bytes')->sortable(),
            Text::make('Mimetype')->sortable()->filterable(),
            Text::make('Locale')->sortable()->filterable(),
            Text::make('Resolution')->sortable()->filterable(),
        ];
    }
}
