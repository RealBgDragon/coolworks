<link rel="stylesheet" href="/css/models.css">
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
    <!-- Left Column (Personal Info) -->
    <div class="col-md-6">
        <?php echo $form->field($model, 'name') ?>
        <?php echo $form->field($model, 'phone')->numberField() ?>
        <?php echo $form->field($model, 'birthday')->dateField() ?>
        <?php echo $form->field($model, 'height')->numberField() ?>
        <?php echo $form->field($model, 'weight')->numberField() ?>
        <?php echo $form->field($model, 'city') ?>
        <?php echo $form->field($model, 'clothes_size') ?>
        <?php echo $form->field($model, 'shoes_size') ?>
        <?php echo $form->field($model, 'jeans_size') ?>
        <?php echo $form->field($model, 'pants_size') ?>
    </div>

    <!-- Right Column (Appearance & Uploads) -->
    <div class="col-md-6">
        <label for="eye_color">Eye Color:</label>
        <div class="radio-group">
            <?php
            foreach ($eyeColorOptions as $eyeColor) {
                ?>
                <label><input type="radio" name="eye_color" value="<?php echo $eyeColor['eye_color_id']; ?>">
                    <?php echo $eyeColor['name']; ?>
                </label>
            <?php } ?>
        </div>

        <label for="hair_color">Hair Color:</label>
        <div class="radio-group">
            <?php
            foreach ($hairColorOptions as $hairColor) {
                ?>
                <label><input type="radio" name="hair_color" value="<?php echo $hairColor['hair_color_id']; ?>">
                    <?php echo $hairColor['name']; ?>
                </label>
            <?php } ?>
        </div>

        <label for="gender">Gender:</label>
        <div class="radio-group">
            <label><input type="radio" name="gender" value="<?php echo ModelOptions::GENDER_MALE; ?>"> Male</label>
            <label><input type="radio" name="gender" value="<?php echo ModelOptions::GENDER_FEMALE; ?>"> Female</label>
        </div>

        <div class="mb-3">
            <label for="main_image" class="form-label">Main Image</label>
            <input class="form-control" type="file" id="main_image" name="main_image">
        </div>

        <div class="mb-3">
            <label for="additional_images" class="form-label">Additional Images</label>
            <input class="form-control" type="file" id="additional_images" name="additional_images[]" multiple>
        </div>

        <label for="note">Additional Notes:</label>
        <textarea class="form-control" id="note" name="note" rows="3"></textarea>

    </div>
</div>

<div class="row">
    <!--     <label for="talant">Talent:</label>
    <div class="radio-group">
        <label><input type="radio" name="talant" value="<?php echo ModelOptions::TALANT_ACTOR; ?>">
            Actor</label>
        <label><input type="radio" name="talant" value="<?php echo ModelOptions::TALANT_PHOTO_MODEL; ?>"> Photo
            model</label>
    </div>

    <label for="language">Language:</label>
    <div class="checkbox-group">
        <label><input type="checkbox" name="language[]" value="<?php echo ModelOptions::LANGUAGE_BULGARIAN; ?>">
            Bulgarian</label>
        <label><input type="checkbox" name="language[]" value="<?php echo ModelOptions::LANGUAGE_ENGLISH; ?>">
            English</label>
    </div> -->
</div>

<div class="button-div">
    <button type="submit" class="btn btn-primary">Add model <i class="bi bi-save"></i></button>
</div>

<?php Form::end() ?>

<script src="c:\xampp\htdocs\coolworks\views\newModel.js"></script>