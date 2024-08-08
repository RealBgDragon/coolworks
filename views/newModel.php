<link rel="stylesheet" href="/css/newModel.css">
<h1>Add New Model</h1>
<?php
use app\core\form\Form;
use app\core\form\Slider;
use app\models\PhotoModel;
use app\core\form\ModelOptions;

$form = Form::begun('', "post", ['enctype' => 'multipart/form-data']);

if (!isset($model)) {
    $model = new PhotoModel();
}
?>

<div class="row">
    <div class="col-md-6">
        <?php echo $form->field($model, 'name') ?>
        <?php echo $form->field($model, 'phone')->numberField() ?>
        <?php echo $form->field($model, 'height')->numberField() ?>
        <label for="eye_color">Eye Color:</label>
        <div class="radio-group">
            <label><input type="radio" name="eye_color" value="<?php echo ModelOptions::EYE_COLOR_BLUE; ?>">
                Blue</label>
            <label><input type="radio" name="eye_color" value="<?php echo ModelOptions::EYE_COLOR_GREEN; ?>">
                Green</label>
            <label><input type="radio" name="eye_color" value="<?php echo ModelOptions::EYE_COLOR_BROWN; ?>">
                Brown</label>
        </div>
        <label for="talant">Talant:</label>
        <div class="radio-group">
            <label><input type="radio" name="talant" value="<?php echo ModelOptions::TALANT_ACTOR; ?>">
                Actor</label>
            <label><input type="radio" name="talant" value="<?php echo ModelOptions::TALANT_PHOTO_MODEL; ?>">
                Photo model</label>
        </div>
        <label for="language">Language:</label>
        <div class="checkbox-group">
            <label><input type="checkbox" name="language[]" value="<?php echo ModelOptions::LANGUAGE_BULGARIAN; ?>">
                Bulgarian</label>
            <label><input type="checkbox" name="language[]" value="<?php echo ModelOptions::LANGUAGE_ENGLISH; ?>">
                English</label>
        </div>

    </div>
    <div class="col-md-6">
        <?php echo $form->field($model, 'weight')->numberField() ?>
        <?php echo $form->field($model, 'birthday')->dateField() ?>
        <div class="mb-3">
            <label for="main_image" class="form-label">Main Image</label>
            <input class="form-control" type="file" id="main_image" name="main_image">
        </div>
        <label for="hair_color">Hair Color:</label>
        <div class="radio-group">
            <label><input type="radio" name="hair_color" value="<?php echo ModelOptions::HAIR_COLOR_BLACK; ?>">
                Black</label>
            <label><input type="radio" name="hair_color" value="<?php echo ModelOptions::HAIR_COLOR_BROWN; ?>">
                Brown</label>
            <label><input type="radio" name="hair_color" value="<?php echo ModelOptions::HAIR_COLOR_BLONDE; ?>">
                Blonde</label>
        </div>
        <div class="mb-3">
            <label for="additional_images" class="form-label">Additional Images</label>
            <input class="form-control" type="file" id="additional_images" name="additional_images[]" multiple>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">Add model <i class="bi bi-save"></i></button>

<?php Form::end() ?>


<script src="c:\xampp\htdocs\coolworks\views\newModel.js"></script>