<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use PhUml\Code\Name;

/**
 * It represents a variable declaration
 */
class Variable implements HasType
{
    use WithTypeDeclaration;

    /** @var string */
    protected $name;

    protected function __construct(string $name, TypeDeclaration $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public static function declaredWith(string $name, TypeDeclaration $type = null): Variable
    {
        return new Variable($name, $type ?? TypeDeclaration::absent());
    }

    public function __toString()
    {
        return sprintf(
            '%s%s',
            $this->name,
            $this->type->isPresent() ? ": {$this->type}" : ''
        );
    }

    /**
     * References to arrays need to have the `[]` removed from their names in order to create
     * external definitions with a proper name
     *
     * The edges created from these references need to map to the names without the suffix
     *
     * @see \PhUml\Parser\Code\ExternalAssociationsResolver::resolveExternalAttributes()
     * @see \PhUml\Parser\Code\ExternalAssociationsResolver::resolveExternalConstructorParameters()
     * @see \PhUml\Graphviz\Builders\EdgesBuilder::addAssociation()
     */
    public function referenceName(): Name
    {
        return $this->isArray() ? $this->arrayTypeName() : $this->typeName();
    }

    private function typeName(): Name
    {
        return $this->type->name();
    }

    private function isArray(): bool
    {
        return $this->type->isArray();
    }

    private function arrayTypeName(): Name
    {
        return Name::from($this->type->removeArraySuffix());
    }
}
