<?php

namespace pierresilva\Backup\Exceptions;

use Exception;

class NotificationCouldNotBeSent extends Exception
{
    public static function noNotifcationClassForEvent($event): self
    {
        $eventClass = get_class($event);

        return new static("There is no notification class that can handle event `{$eventClass}`.");
    }
}
