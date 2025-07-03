<?php

namespace App\Nova\Concerns;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

trait ReadonlyResource
{
    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }

    public function authorizedToForceDelete(Request $request): bool
    {
        return false;
    }

    public function authorizedToRestore(Request $request): bool
    {
        return false;
    }

    public function authorizedToAdd(NovaRequest $request, $model): bool
    {
        return false;
    }

    public function authorizedToAttachAny(NovaRequest $request, $model): bool
    {
        return false;
    }

    public function authorizedToAttach(NovaRequest $request, $model): bool
    {
        return false;
    }

    public function authorizedToDetach(NovaRequest $request, $model, $relationship): bool
    {
        return false;
    }

    public function authorizedToReplicate(Request $request): bool
    {
        return false;
    }
}
