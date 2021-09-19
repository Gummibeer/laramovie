<?php

namespace App\Console\Commands;

use Illuminate\Console\Command as IlluminateCommand;
use Symfony\Component\Console\Helper\ProgressBar;

abstract class Command extends IlluminateCommand
{
    public function startProgressBar(int $max): ProgressBar
    {
        $progressBar = $this->output->createProgressBar($max);
        $progressBar->setFormat('very_verbose');
        $progressBar->setRedrawFrequency(1);
        $progressBar->minSecondsBetweenRedraws(0);
        $progressBar->maxSecondsBetweenRedraws(1);
        $progressBar->start();

        return $progressBar;
    }
}
