<link rel="stylesheet" href="/css/newModels.css">
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
    </div>
    <div class="col-md-6">
        <?php echo $form->field($model, 'weight')->numberField() ?>
        <?php echo $form->field($model, 'birthday')->dateField() ?>
        <div class="mb-3">
            <label for="image_url" class="form-label">Image</label>
            <input class="form-control" type="file" id="image_url" name="image_url" value="/">
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
    </div>

</div>

<button type="submit" class="btn btn-primary">Add model</button>

<?php Form::end() ?>

<script>
    // Get the slider and value display elements
    const slider = document.getElementById("customRange2");
    const sliderValue = document.getElementById("sliderValue");

    // Update the value display when the slider value changes
    slider.addEventListener("input", function () {
        sliderValue.textContent = this.value;
    });
</script>
<script src="c:\xampp\htdocs\coolworks\views\newModel.js"></script>