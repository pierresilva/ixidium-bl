<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class Name
{
    /** @var string */
    private $name;

    public static function from(string $text): Name
    {
        return new Name($text);
    }

    public function __toString()
    {
        return $this->name;
    }

    private function __construct(string $name)
    {
        $this->name = $name;
    }
}
