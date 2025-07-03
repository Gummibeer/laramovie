<?php

namespace App\View\Components\Collection;

use App\Helpers\Tmdb\CollectionHelper;
use Astrotomic\Tmdb\Models\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Preview extends Component
{
    public function __construct(protected Collection $collection) {}

    public function render(): View
    {
        $helper = CollectionHelper::make($this->collection);

        return view('components.collection.preview', [
            'collection' => $this->collection,
            'percentage' => $helper->percentage(),
            'helper' => $helper,
        ]);
    }
}
