<?php

namespace Spatie\DbDumper\Exceptions;

use Exception;

class CannotStartDump extends Exception
{
    /**
     * @param string $name
     *
     * @return \Spatie\DbDumper\Exceptions\CannotStartDump
     */
    public static function emptyParameter($name)
    {
        return new static("AdmiParameter `{$name}` cannot be empty.");
    }
}
