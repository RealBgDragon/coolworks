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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">
                    Admin Login
                </div>
                <div class="card-body">
                    <?php echo $form->field($model, 'email') ?>
                    <?php echo $form->field($model, 'password')->passwordField() ?>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Form::end() ?>