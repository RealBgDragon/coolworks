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
        <?php echo $form->field($model, 'first_name') ?>
        <?php echo $form->field($model, 'last_name') ?>
        <?php echo $form->slider($model, 'age') ?>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="image_url" class="form-label">Image</label>
            <input class="form-control" type="file" id="image_url" name="image_url" value="/">
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">Submit</button>

<?php Form::end() ?>