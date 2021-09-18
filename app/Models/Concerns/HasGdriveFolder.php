<?php

namespace App\Models\Concerns;

use Google\Service\Drive;

trait HasGdriveFolder
{
    public function gdrive_link(): string
    {
        return app(Drive::class)->files->get($this->gdrive_id, [
            'fields' => 'webViewLink',
        ])->getWebViewLink();
    }
}
