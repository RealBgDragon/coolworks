<h1>Add New Model</h1>
<?php
use app\core\form\Form;
use app\core\form\Slider;
use app\models\PhotoModel;

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
    </div>
    <div class="col-md-6">
        <?php echo $form->field($model, 'weight')->numberField() ?>
        <?php echo $form->field($model, 'birthday')->dateField() ?>
        <div class="mb-3">
            <label for="image_url" class="form-label">Image</label>
            <input class="form-control" type="file" id="image_url" name="image_url" value="/">
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