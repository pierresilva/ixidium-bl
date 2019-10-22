<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

/**
 * It will ignore the methods of a definition, and therefore its filters
 */
class NoMethodsBuilder extends MethodsBuilder
{
    public function __construct()
    {
        parent::__construct([]);
    }

    public function build(array $methods): array
    {
        return [];
    }
}
