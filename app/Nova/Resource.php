<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;

abstract class Resource extends NovaResource
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public static function detailQuery(NovaRequest $request, $query): Builder
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public static function relatableQuery(NovaRequest $request, $query): Builder
    {
        return parent::relatableQuery($request, $query);
    }
}
