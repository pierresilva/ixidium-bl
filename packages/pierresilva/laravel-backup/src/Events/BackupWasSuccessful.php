<?php

namespace pierresilva\Backup\Events;

use pierresilva\Backup\BackupDestination\BackupDestination;

class BackupWasSuccessful
{
    /** @var \Spatie\Backup\BackupDestination\BackupDestination */
    public $backupDestination;

    public function __construct(BackupDestination $backupDestination)
    {
        $this->backupDestination = $backupDestination;
    }
}
