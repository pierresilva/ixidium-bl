<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

/**
 * Both `ClassDefinition` and `InterfaceDefinition` have a DOT language representation.
 * `Edge`s can be represented as DOT language too
 */
interface HasDotRepresentation
{
    public function dotTemplate(): string;
}
