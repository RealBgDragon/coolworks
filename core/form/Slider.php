<?php

namespace app\core\form;

use app\core\Model;

class Slider
{
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        return sprintf('
        <div class="mb-3">
                <label class="form-label">%s</label>
                <div class="slider-container">
                <span id="sliderValue">25</span>
                <input name="%s" type="range" class="form-range" min="1" max="100" id="customRange2" value="%s">
                </div>
        </div>'
            ,
            $this->model->getLabel($this->attribute), //label
            $this->attribute,
            $this->model->{$this->attribute} ?? '25',
            $this->model->getFirstError($this->attribute)
        );
    }
}
