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

    public static function getEyeColorName($eyeColor)
    {
        switch ($eyeColor) {
            case self::EYE_COLOR_BLUE:
                return 'Blue';
            case self::EYE_COLOR_GREEN:
                return 'Green';
            case self::EYE_COLOR_BROWN:
                return 'Brown';
            default:
                return 'Unknown';
        }
    }

    public static function getHairColorName($hairColor)
    {
        switch ($hairColor) {
            case self::HAIR_COLOR_BLONDE:
                return 'Blonde';
            case self::HAIR_COLOR_BROWN:
                return 'Brown';
            case self::HAIR_COLOR_BLACK:
                return 'Black';
            default:
                return 'Unknown';
        }
    }
}
