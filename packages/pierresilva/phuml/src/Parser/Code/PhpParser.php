<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhpParser\Parser;
use PhUml\Code\Codebase;
use PhUml\Parser\CodeFinder;

abstract class PhpParser
{
    /** @var Parser */
    private $parser;

    /** @var PhpTraverser */
    private $traverser;

    public function __construct(Parser $parser, PhpTraverser $traverser)
    {
        $this->parser = $parser;
        $this->traverser = $traverser;
    }

    public function parse(CodeFinder $finder): Codebase
    {
        foreach ($finder->files() as $code) {
            $this->traverser->traverse($this->parser->parse($code));
        }
        return $this->traverser->codebase();
    }
}
