<?php

namespace App\Nova;

use App\Nova\Actions\InsertFileNames;
use App\Nova\Concerns\ReadonlyResource;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DownloadedFile extends Resource
{
    use ReadonlyResource;

    public static $model = \App\Models\DownloadedFile::class;

    public static $title = 'file_name';

    public static $search = [
        'file_name',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('File Name', 'file_name')->sortable(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            InsertFileNames::make()->standalone(),
        ];
    }
}
