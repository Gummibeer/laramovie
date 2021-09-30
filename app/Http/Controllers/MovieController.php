<?php

namespace App\Http\Controllers;

use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Contracts\View\View as ViewContract;

class MovieController
{
    public function index(): ViewContract
    {
        return view('movies');
    }

    public function show(Movie $movie): ViewContract
    {
        return view('movie', [
            'movie' => $movie,
        ]);
    }
}
