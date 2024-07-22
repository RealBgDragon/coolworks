<?php

namespace app\core\form;

class ModelOptions
{
    const EYE_COLOR_BLUE = 1 << 0; // 00000001
    const EYE_COLOR_GREEN = 1 << 1; // 00000010
    const EYE_COLOR_BROWN = 1 << 2; // 00000100

    const HAIR_COLOR_BLONDE = 1 << 3; // 00001000
    const HAIR_COLOR_BROWN = 1 << 4; // 00010000
    const HAIR_COLOR_BLACK = 1 << 5; // 00100000

    public static function isOptionSet($options, $option)
    {
        return ($options & $option) === $option;
    }
}