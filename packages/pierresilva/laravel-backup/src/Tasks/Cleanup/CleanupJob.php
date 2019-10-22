<?php

namespace pierresilva\Backup\Tasks\Cleanup;

use Exception;
use pierresilva\Backup\Helpers\Format;
use Illuminate\Support\Collection;
use pierresilva\Backup\Events\CleanupHasFailed;
use pierresilva\Backup\Events\CleanupWasSuccessful;
use pierresilva\Backup\BackupDestination\BackupDestination;

class CleanupJob
{
    /** @var \Illuminate\Support\Collection */
    protected $backupDestinations;

    /** @var \pierresilva\Backup\Tasks\Cleanup\CleanupStrategy */
    protected $strategy;

    /** @var bool */
    protected $sendNotifications = true;

    public function __construct(Collection $backupDestinations, CleanupStrategy $strategy, bool $disableNotifications)
    {
        $this->backupDestinations = $backupDestinations;

        $this->strategy = $strategy;

        $this->sendNotifications = ! $disableNotifications;
    }

    public function run()
    {
        $this->backupDestinations->each(function (BackupDestination $backupDestination) {
            try {
                if (! $backupDestination->isReachable()) {
                    throw new Exception("Could not connect to disk {$backupDestination->diskName()} because: {$backupDestination->connectionError()}");
                }

                consoleOutput()->info("Cleaning backups of {$backupDestination->backupName()} on disk {$backupDestination->diskName()}...");

                $this->strategy->deleteOldBackups($backupDestination->backups());
                $this->sendNotification(new CleanupWasSuccessful($backupDestination));

                $usedStorage = Format::humanReadableSize($backupDestination->usedStorage());
                consoleOutput()->info("Used storage after cleanup: {$usedStorage}.");
            } catch (Exception $exception) {
                consoleOutput()->error("Cleanup failed because: {$exception->getMessage()}.");

                $this->sendNotification(new CleanupHasFailed($exception));

                throw $exception;
            }
        });
    }

    protected function sendNotification($notification)
    {
        if ($this->sendNotifications) {
            event($notification);
        }
    }
}
