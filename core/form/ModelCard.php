<div class="row">
    <?php
    use app\core\form\Form;
    use app\core\form\ModelOptions;
    use app\models\PhotoModel;
    foreach ($modelsData as $model) {
        $eyeColor = ModelOptions::getEyeColorName($model['eye_color']);
        $hairColor = ModelOptions::getHairColorName($model['hair_color']);
        $talant = ModelOptions::getTalantName($model['talant']);
        $languages = ModelOptions::getLanguages($model['language']);
        $gender = ModelOptions::getGenderName($model['gender']);

        $isSelected = in_array($model['model_id'], $selectedModels);
        ?>
        <div class="col-md-2 mb-4">
            <?php if ($isSelected): ?>
                <div class="card model-card" style="background-color:#f8f9fa">
                <?php else: ?>
                    <div class="card model-card">
                    <?php endif; ?>
                    <div class="position-relative">
                        <?php $photoModel = new PhotoModel() ?>
                        <img src="<?php echo $photoModel->getImagePath($model['model_id']) ?>"
                            onerror="this.onerror=null; this.src='/uploads/default-image.jpg';"
                            class="card-img-top img-fluid" alt="Model Image" style="width: 300px; height: 300px;"
                            data-toggle="modal" data-target="#modelModal" data-model-id="<?php echo $model['model_id']; ?>"
                            data-model-name="<?php echo $model['name']; ?>" data-model-age="<?php echo $model['age']; ?>"
                            data-model-height="<?php echo $model['height']; ?>"
                            data-model-weight="<?php echo $model['weight']; ?>"
                            data-model-birthday="<?php echo $model['birthday']; ?>"
                            data-model-srcs="<?php echo $photoModel->getAllImagePaths($model['model_id']); ?>"
                            data-model-phone="<?php echo $model['phone']; ?>" data-eye-color="<?php echo $eyeColor; ?>"
                            data-hair-color="<?php echo $hairColor; ?>" data-talant="<?php echo $talant; ?>"
                            data-language="<?php echo $languages; ?>" data-gender="<?php echo $gender; ?>"
                            onclick="showModelDetails(this)">

                        <?php $form = Form::begun('', "post"); ?>
                        <input type="hidden" name="model_id" value="<?php echo $model['model_id']; ?>">
                        <input type="hidden" name="admin_id" value="<?php echo $_SESSION['admin']; ?>">
                        <?php if ($isSelected): ?>
                            <button type="button" class="btn btn-success position-absolute bottom-0 end-0 m-2">
                                <i class="bi bi-person-fill-check"></i>
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary position-absolute bottom-0 end-0 m-2">
                                <i class="bi bi-person-add"></i>
                            </button>
                        <?php endif; ?>
                        <?php Form::end(); ?>
                    </div>

                    <div class="card-body text-center">
                        <p class="card-text"><?php echo $model['name']; ?></p>
                        <p class="card-text">Age: <?php echo $model['age']; ?> | Height: <?php echo $model['height']; ?>
                        </p>
                        <p class="card-text"> Weight: <?php echo $model['weight']; ?></p>
                        <p> Talants: <?php echo $talant; ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>