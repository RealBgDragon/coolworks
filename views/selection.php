<?php use app\core\form\Form;
use app\core\form\ModelOptions;
use app\models\PhotoModel;
use app\models\SelectModel;

if (!isset($model)) {
    $model = new SelectModel();
}
?>
<link rel="stylesheet" href="/css/models.css">
<div class="col-md-auto d-flex justify-content-start mb-4">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="savedSelectionsDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            Saved Selections
        </button>
        <ul class="dropdown-menu" aria-labelledby="savedSelectionsDropdown">
            <li><a class="dropdown-item" href="selection">New</a></li>
            <?php
            $last = '';
            foreach ($selectionOptions as $selection) {
                if ($selection === $last) {
                    continue;
                }
                $last = $selection;
                ?>
                <li><a class="dropdown-item" href="selection?name=<?php echo $selection ?>"><?php echo $selection ?></a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="col-md-auto d-flex justify-content-end mb-4">
    <?php $form = Form::begun('', "post"); ?>
    <?php echo $form->field($model, 'selection_name') ?>
    <button type="submit" name="save_selection" class="btn btn-primary btn-sm">Save selection <i
            class="bi bi-floppy2-fill"></i></button>
    <?php $form::end(); ?>
</div>

<div class="row">
    <?php foreach ($modelsData as $model) {
        $eyeColor = ModelOptions::getEyeColorName($model['eye_color']);
        $hairColor = ModelOptions::getHairColorName($model['hair_color']);
        $talant = ModelOptions::getTalantName($model['talant']);
        $languages = ModelOptions::getLanguages($model['language']); // Updated line
    
        ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <?php $photoModel = new PhotoModel() ?>
                <img src="<?php echo $photoModel->getImagePath($model['model_id']) ?>" class="card-img-top img-fluid"
                    alt="Model Image" style="width: 300px; height: 300px;" data-toggle="modal" data-target="#modelModal"
                    data-model-id="<?php echo $model['model_id']; ?>" data-model-name="<?php echo $model['name']; ?>"
                    data-model-age="<?php echo $model['age']; ?>" data-model-height="<?php echo $model['height']; ?>"
                    data-model-weight="<?php echo $model['weight']; ?>"
                    data-model-birthday="<?php echo $model['birthday']; ?>"
                    data-model-srcs="<?php echo $photoModel->getAllImagePaths($model['model_id']); ?>"
                    data-model-phone="<?php echo $model['phone']; ?>" data-eye-color="<?php echo $eyeColor; ?>"
                    data-hair-color="<?php echo $hairColor; ?>" data-talant="<?php echo $talant; ?>"
                    data-language="<?php echo $languages; ?>" onclick="showModelDetails(this)"> <!-- Updated line -->

                <div class="card-body">
                    <?php $form = Form::begun('', "post"); ?>
                    <p class="card-text"><?php echo $model['name'] ?></p>
                    <p class="card-text"><?php echo $model['height']; ?></p>
                    <p class="card-text"><?php echo $model['age'] ?></p>
                    <input type="text" name="model_id" style="display:none;" value="<?php echo $model['model_id'] ?>">
                    <input type="text" name="admin_id" style="display:none;" value="<?php echo $_SESSION['admin'] ?>">
                    <button type="submit" class="btn btn-primary">Select model <i class="bi bi-folder"></i></button>
                    <?php Form::end() ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="modal fade" id="modelModal" tabindex="-1" role="dialog" aria-labelledby="modelModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelModalLabel">Model Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modelModalBody">
                <div class="row">
                    <div class="col-md-6">
                        <div id="modelCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner" id="carouselInner">
                                <!-- Images will be dynamically added here -->
                            </div>
                            <a class="carousel-control-prev" href="#modelCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true" style="color: black"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#modelCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 id="modelName"></h4>
                        <p>Age: <span id="modelAge"></span></p>
                        <p>Height: <span id="modelHeight"></span></p>
                        <p>Weight: <span id="modelWeight"></span></p>
                        <p>Birthday: <span id="modelBirthday"></span></p>
                        <p>Phone: <span id="modelPhone"></span></p>
                        <p>Eye Color: <span id="modelEyeColor"></span></p>
                        <p>Hair Color: <span id="modelHairColor"></span></p>
                        <p>Talant: <span id="modelTalant"></span></p>
                        <p>Language: <span id="modelLanguage"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/models.js"></script>
<script src="/js/selection.js"></script>