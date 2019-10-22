<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use RuntimeException;

class UnknownImageProcessor extends RuntimeException
{
    /** @param string[] $validNames */
    public static function named(?string $name, array $validNames): UnknownImageProcessor
    {
        return new UnknownImageProcessor($name, $validNames);
    }

    public function __construct(?string $name, array $validNames)
    {
        parent::__construct(sprintf(
            'Invalid processor "%s" found, expected processors are: %s',
            $name,
            implode(', ', $validNames)
        ));
    }
}
