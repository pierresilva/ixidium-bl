<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\Codebase;
use PhUml\Code\InterfaceDefinition;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\Node;

/**
 * It produces the collection of nodes and edges related to an interface
 *
 * It creates a node with the interface itself
 * It creates one or more edges for every interface it extends, if any
 */
class InterfaceGraphBuilder
{
    /**
     * The order in which the nodes and edges are created is as follows
     *
     * 1. The node representing the interface itself
     * 2. The parent interface, if any
     *
     * @param InterfaceDefinition $interface
     * @param Codebase $codebase
     * @return \PhUml\Graphviz\HasDotRepresentation[]
     */
    public function extractFrom(InterfaceDefinition $interface, Codebase $codebase): array
    {
        $dotElements = [];

        $dotElements[] = new Node($interface);

        foreach ($interface->parents() as $parent) {
            $dotElements[] = Edge::inheritance($codebase->get($parent), $interface);
        }

        return $dotElements;
    }
}
