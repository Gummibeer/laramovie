<?php

namespace App\Nova\Actions;

use App\Models\DownloadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class InsertFileNames extends Action
{
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $files = Str::of($fields->get('files'))
            ->trim()
            ->explode(PHP_EOL)
            ->map(fn (string $file) => trim($file))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->map(fn (string $file) => ['file_name' => $file]);

        DownloadedFile::query()->insertOrIgnore($files->all());

        return Action::message('Inserted file names.');
    }

    public function fields(NovaRequest $request): array
    {
        return [
            Textarea::make('Files', 'files')
                ->required(),
        ];
    }
}
