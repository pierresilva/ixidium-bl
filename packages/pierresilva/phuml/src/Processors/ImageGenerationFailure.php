<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use RuntimeException;

/**
 * It is thrown when either the `dot` or `neato` commands fail
 */
class ImageGenerationFailure extends RuntimeException
{
    public static function withOutput(string $errorMessage): ImageGenerationFailure
    {
        return new ImageGenerationFailure("Execution of external program failed:\n{$errorMessage}");
    }
}
