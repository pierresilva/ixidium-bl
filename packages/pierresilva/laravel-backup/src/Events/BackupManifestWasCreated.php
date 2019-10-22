<?php

namespace pierresilva\Backup\Events;

use pierresilva\Backup\Tasks\Backup\Manifest;

class BackupManifestWasCreated
{
    /** @var \pierresilva\Backup\Tasks\Backup\Manifest */
    public $manifest;

    public function __construct(Manifest $manifest)
    {
        $this->manifest = $manifest;
    }
}
