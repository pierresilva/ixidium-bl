<?php

namespace pierresilva\DbDumper\Exceptions;

use Exception;

class CannotStartDump extends Exception
{
    /**
     * @param string $name
     *
     * @return \pierresilva\DbDumper\Exceptions\CannotStartDump
     */
    public static function emptyParameter($name)
    {
        return new static("Parameter `{$name}` cannot be empty.");
    }
}
