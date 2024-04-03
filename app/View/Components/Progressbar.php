<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Progressbar extends Component
{
    public function __construct(
        protected float $current,
        protected float $total,
    ) {
    }

    public function render(): View
    {
        return view('components.progressbar', [
            'current' => $this->current,
            'total' => $this->total,
            'percentage' => max(0, min($this->current / $this->total * 100, 100)),
        ]);
    }
}
