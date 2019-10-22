<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

trait WithNumericIds
{
    /** @before */
    function resetIds(): void
    {
        NumericIdClass::reset();
        NumericIdInterface::reset();
        NumericIdTrait::reset();
    }
}
