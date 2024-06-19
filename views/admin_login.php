<h1>Login</h1>
<?php
use app\core\form\Form;
use app\models\LoginForm;

$form = Form::begun('', "post");
?>

<?php

if (!isset($model)) {
    $model = new LoginForm();
} ?>






<form>
    <div class="form-group">
        <?php echo $form->field($model, 'email') ?>
    </div>
    <div class="form-group">
        <?php echo $form->field($model, 'password')->passwordField() ?>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php Form::end() ?>