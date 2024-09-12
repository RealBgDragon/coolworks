<?php

namespace app\core\form;
use app\models\PhotoModel;

class ModelOptions
{
    const TALANT_ACTOR = 1 << 0; // 00000001 //1
    const TALANT_PHOTO_MODEL = 1 << 1; // 00000010 //2

    const LANGUAGE_BULGARIAN = 1 << 0; // 00000001 //1
    const LANGUAGE_ENGLISH = 1 << 1; // 00000010 //2

    const EYE_COLOR_BLUE = 1 << 0; // 00000001 //1
    const EYE_COLOR_GREEN = 1 << 1; // 00000010 //2
    const EYE_COLOR_BROWN = 1 << 2; // 00000100 //4

    const HAIR_COLOR_BLONDE = 1 << 3; // 00001000 //16
    const HAIR_COLOR_BROWN = 1 << 4; // 00010000 //32
    const HAIR_COLOR_BLACK = 1 << 5; // 00100000 //64

    const GENDER_MALE = 1 << 0;  // 00000001 //1
    const GENDER_FEMALE = 1 << 1; // 00000010 //2
    const GENDER_CHILD = 1 << 2; // 00000100 //4

    public static function getTalantName($talentId)
    {
        $photoModel = new PhotoModel();
        $join = "models_talents mt ON t.talent_id = mt.talent_id";
        $talent = $photoModel->getNameFromDb('name', 'talent_id = :id', [':id' => $talentId], 'talents', $join);
        return $talent ? $talent[0] : 'Unknown';
    }


    public static function getLanguages($languageBitmask)
    {
        $languages = [];
        if ($languageBitmask & self::LANGUAGE_BULGARIAN) {
            $languages[] = 'Bulgarian';
        }
        if ($languageBitmask & self::LANGUAGE_ENGLISH) {
            $languages[] = 'English';
        }
        return $languages ? implode(', ', $languages) : 'Unknown';
    }
    public static function getEyeColorName($eyeColorId)
    {
        $photoModel = new PhotoModel();
        $eyeColor = $photoModel->getNameFromDb('name', 'eye_color_id = :id', [':id' => $eyeColorId], 'eye_colors');
        return $eyeColor ? $eyeColor[0] : 'Unknown';
    }

    public static function getHairColorName($hairColorId)
    {
        $photoModel = new PhotoModel();
        $hairColor = $photoModel->getNameFromDb('name', 'hair_color_id = :id', [':id' => $hairColorId], 'hair_colors');

        return $hairColor ? $hairColor[0] : 'Unknown';
    }

    public static function getGenderName($gender)
    {
        switch ($gender) {
            case self::GENDER_MALE:
                return 'Male';
            case self::GENDER_FEMALE:
                return 'Female';
            case self::GENDER_CHILD:
                return 'Child';
            default:
                return 'Unknown';
        }
    }
}
