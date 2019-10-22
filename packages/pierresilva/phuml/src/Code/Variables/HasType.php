<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

interface HasType
{
    /**
     * A member is a reference if it has a type and it's not a built-in type
     *
     * This is used when building the digraph and the option `createAssociations` is set
     *
     * @see \PhUml\Graphviz\Builders\EdgesBuilder::needAssociation() for more details
     */
    public function isAReference(): bool;

    /**
     * This is used to build the `Summary` of a `Structure`
     *
     * @see \PhUml\Code\ClassDefinition::countTypedAttributesByVisibility() for more details
     */
    public function hasTypeDeclaration(): bool;

    /**
     * It is used by the `EdgesBuilder` class to mark an association as resolved
     *
     * @see \PhUml\Graphviz\Builders\EdgesBuilder::needAssociation() for more details
     * @see \PhUml\Graphviz\Builders\EdgesBuilder::markAssociationResolvedFor() for more details
     */
    public function type(): TypeDeclaration;
}
