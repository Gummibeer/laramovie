<?php

namespace App\Http\Controllers;

use App\Models\TvShow;
use Illuminate\Contracts\View\View as ViewContract;

class TvShowController
{
    public function index(): ViewContract
    {
        return view('tvshows');
    }

    public function show(TvShow $tvShow): ViewContract
    {
        $tvShow->load('seasons');

        return view('tvshow', [
            'tvShow' => $tvShow,
        ]);
    }
}
