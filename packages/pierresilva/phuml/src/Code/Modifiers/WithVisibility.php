<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

trait WithVisibility
{
    /** @var Visibility */
    private $modifier;

    public function isPublic(): bool
    {
        return $this->hasVisibility(Visibility::public());
    }

    public function isPrivate(): bool
    {
        return $this->hasVisibility(Visibility::private());
    }

    public function isProtected(): bool
    {
        return $this->hasVisibility(Visibility::protected());
    }

    public function hasVisibility(Visibility $modifier): bool
    {
        return $this->modifier->equals($modifier);
    }
}
