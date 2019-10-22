<?php

namespace pierresilva\DbDumper\Exceptions;

use Exception;

class CannotSetParameter extends Exception
{
    /**
     * @param string $name
     * @param string $conflictName
     *
     * @return \pierresilva\DbDumper\Exceptions\CannotSetParameter
     */
    public static function conflictingParameters($name, $conflictName)
    {
        return new static("Cannot set `{$name}` because it conflicts with parameter `{$conflictName}`.");
    }
}
