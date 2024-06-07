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

<?php echo $form->field($model, 'email') ?>
<?php echo $form->field($model, 'password')->passwordField() ?>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end() ?>