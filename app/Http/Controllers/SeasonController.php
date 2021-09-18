<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\TvShow;
use Illuminate\Contracts\View\View as ViewContract;

class SeasonController
{
    public function show(TvShow $tvShow, Season $season): ViewContract
    {
        return view('season', [
            'tvShow' => $tvShow,
            'season' => $season,
        ]);
    }
}
