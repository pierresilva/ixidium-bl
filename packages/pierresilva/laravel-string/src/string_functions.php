<?php

use pierresilva\String\Str;

/**
 * @param string $string
 *
 * @return \pierresilva\String\Str
 */
function string($string = '')
{
    return new Str($string);
}
