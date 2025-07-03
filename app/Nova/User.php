<?php

namespace App\Nova;

use App\Nova\Concerns\ReadonlyResource;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    use ReadonlyResource;

    public static $model = \App\Models\User::class;

    public static $title = 'nickname';

    public static $search = [
        'slug', 'nickname', 'name',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Gravatar::make()->maxWidth(50),
            Text::make('Nickname', 'nickname')->sortable(),
            Text::make('Name', 'name')->sortable(),
        ];
    }
}
