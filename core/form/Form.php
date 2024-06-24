<?php

namespace app\core\form;

use app\core\Model;

class Form
{
    public static function begun($action, $method, $attributes = [])
    {
        $attributeStr = '';

        foreach ($attributes as $key => $value) {
            $attributeStr = sprintf(' %s="%s"', $key, $value);
        }

        echo sprintf('<form action="%s" method="%s"%s>', $action, $method, $attributeStr);
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model, $attribute)
    {
        return new Field($model, $attribute);
    }

    public function slider(Model $model, $attribute)
    {
        return new Slider($model, $attribute);
    }

    public function video()
    {
        return new Video();
    }
}