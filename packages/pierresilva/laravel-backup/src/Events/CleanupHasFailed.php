<?php

namespace pierresilva\Backup\Events;

use Exception;
use pierresilva\Backup\BackupDestination\BackupDestination;

class CleanupHasFailed
{
    /** @var \Exception */
    public $exception;

    /** @var \Spatie\Backup\BackupDestination\BackupDestination|null */
    public $backupDestination;

    public function __construct(Exception $exception, BackupDestination $backupDestination = null)
    {
        $this->exception = $exception;

        $this->backupDestination = $backupDestination;
    }
}
