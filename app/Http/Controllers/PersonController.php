<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Contracts\View\View as ViewContract;

class PersonController
{
    public function index(): ViewContract
    {
        return view('people');
    }

    public function show(Person $person): ViewContract
    {
        return view('person', [
            'person' => $person,
        ]);
    }
}
