<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

class NoConstantsBuilder extends ConstantsBuilder
{
    public function build(array $classAttributes): array
    {
        return [];
    }
}
