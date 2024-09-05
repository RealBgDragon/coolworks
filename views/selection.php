<?php use app\core\form\Form;
use app\core\form\ModelOptions;
use app\models\PhotoModel;
use app\models\SelectModel;

if (!isset($model)) {
    $model = new SelectModel();
}
?>
<link rel="stylesheet" href="/css/models.css">

<!-- Button to Open the Selection Modal -->
<div class="col-md-auto d-flex justify-content-start mb-4">
    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#selectionModal">
        Saved Selections
    </button>
</div>

<!-- Selection Modal -->
<div class="modal fade" id="selectionModal" tabindex="-1" aria-labelledby="selectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectionModalLabel">Select a saved selection (click name to preview)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><a href="selection" class="selection-link">Current</a></li>
                    <?php
                    $last = '';
                    foreach ($selectionOptions as $selection) {
                        if ($selection['name'] === $last) {
                            continue;
                        }
                        $last = $selection['name'];
                        ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="selection?name=<?php echo $selection['name'] ?>" class="selection-link">
                                    <span class="selection-name"><?php echo $selection['name'] ?></span>
                                    <span class="selection-info">
                                        | Iteration: <?php echo $selection['iterations'] ?> |
                                        <?php
                                        $date = new DateTime($selection['selection_date']);
                                        echo $date->format('d.m.Y H:i');
                                        ?>
                                    </span>
                                </a>
                                <div class="selection-buttons">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#transferModal"
                                        data-selection-name="<?php echo $selection['name']; ?>">Select</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmModal"
                                        data-selection-id="<?php echo $selection['id']; ?>">Delete</button>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Delete conformation -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <?php $form = Form::begun('', "post"); ?>
            <input type="hidden" id="selection_id" name="selection_id" value="">
            <button type="submit" class="btn btn-danger" id="confirmDelete" name="delete_selection">Delete</button>
            <?php $form::end(); ?>
        </div>
    </div>
</div>

<!-- Transfer Confirmation Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferModalLabel">Confirm Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to transfer the models for selection: <span id="selectionNameSpan"></span>?
            </div>
            <?php $form = Form::begun('', "post"); ?>
            <?php echo $form->field($model, 'selection_name') ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning" name="save_selection">Save the initial selection
                    first</button>
                <?php $form::end(); ?>
                <!-- error -->
                <?php $form = Form::begun('', "post"); ?>
                <input type="hidden" id="selection_name" name="selection_name" value="">
                <button type="submit" class="btn btn-primary" name="transfer_selection">Confirm Transfer</button>
                <?php $form::end(); ?>
            </div>
        </div>
    </div>
</div>


<div class=" col-md-auto d-flex justify-content-end mb-4">
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
        $languages = ModelOptions::getLanguages($model['language']);

        ?>
        <div class="col-md-2 mb-4">
            <div class="card model-card">
                <div class="position-relative">
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
                        data-language="<?php echo $languages; ?>" onclick="showModelDetails(this)">

                    <?php $form = Form::begun('', "post"); ?>
                    <input type="hidden" name="model_id" value="<?php echo $model['model_id']; ?>">
                    <input type="hidden" name="admin_id" value="<?php echo $_SESSION['admin']; ?>">
                    <?php if (!isset($_GET['name'])): ?>
                        <button type="submit" name="remove_selection"
                            class="btn btn-danger position-absolute bottom-0 end-0 m-2">
                            <i class="bi bi-person-fill-x"></i>
                        </button>
                    <?php endif ?>
                    <?php Form::end() ?>
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

<div class="modal fade" id="modelModal" tabindex="-1" role="dialog" aria-labelledby="modelModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelModalLabel">Model Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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