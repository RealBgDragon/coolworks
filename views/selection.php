<?php use app\core\form\Form;
use app\core\form\ModelOptions;
use app\models\PhotoModel;
use app\models\SelectModel;

if (!isset($model)) {
    $model = new SelectModel();
}
?>
<link rel="stylesheet" href="/css/models.css">

<!-- Selection Modal -->
<div class="modal fade" id="selectionModal" tabindex="-1" aria-labelledby="selectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectionModalLabel">Select a saved selection (click name to preview)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 55%;">Selection Name</th>
                                <th style="width: 5%;">Iteration</th>
                                <th style="width: 20%;">Date</th>
                                <th style="width: 20%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="selection" class="selection-link" style="font-weight: bold;">Current</a>
                                </td>
                                <td>-</td>
                                <td>-</td>
                                <td></td>
                            </tr>
                            <?php
                            $last = '';
                            foreach ($selectionOptions as $selection) {
                                if ($selection['name'] === $last) {
                                    continue;
                                }
                                $last = $selection['name'];
                                ?>
                                <tr>
                                    <td>
                                        <a href="selection?name=<?php echo $selection['name'] ?>" class="selection-link">
                                            <?php echo $selection['name'] ?>
                                        </a>
                                    </td>
                                    <td><?php echo $selection['iterations'] ?></td>
                                    <td>
                                        <?php
                                        $date = new DateTime($selection['selection_date']);
                                        echo $date->format('d.m.Y H:i');
                                        ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#transferModal"
                                            data-selection-name="<?php echo $selection['name']; ?>">Select</button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#confirmModal"
                                            data-selection-id="<?php echo $selection['id']; ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Delete conformation -->
<div class="modal fade " id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="model-footer d-flex justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    style="margin: 5px; margin-bottom: 10px">Cancel</button>
                <?php $form = Form::begun('', "post"); ?>
                <input type="hidden" id="selection_id" name="selection_id" value="">
                <button type="submit" class="btn btn-danger" id="confirmDelete" name="delete_selection"
                    style="margin: 5px">Delete</button>
                <?php $form::end(); ?>
            </div>
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
                <?php $form = Form::begun('', "post"); ?>
                <div class="input-group">
                    <input type="text" name="selection_name" value="" placeholder="Enter selection name"
                        class="form-control">
                    <button type="submit" name="save_selection" class="btn btn-warning">Save the current selection
                    </button>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

<!-- Button to Open the Selection Modal -->
<div class="row d-flex justify-content-between">
    <div class="col-md-auto d-flex justify-content-start mb-4">
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#selectionModal">
            Saved Selections
        </button>
    </div>
    <div class="col-md-auto d-flex justify-content-end mb-4">
        <?php $form = Form::begun('', "post"); ?>
        <div class="input-group">
            <input type="text" name="selection_name" value="" placeholder="Enter new selection name"
                class="form-control">
            <button type="submit" name="save_selection" class="btn btn-primary">Save <i
                    class="bi bi-floppy2-fill"></i></button>
        </div>
        <?php $form::end(); ?>
    </div>
</div>


<div class="row">
    <?php foreach ($modelsData as $index => $model) {
        $eyeColor = ModelOptions::getEyeColorName($model['eye_color']);
        $hairColor = ModelOptions::getHairColorName($model['hair_color']);
        $model['talant_id'] = explode(',', $model['talant_id']);
        $model['language_id'] = explode(',', $model['language_id']);
        $talants = [];
        foreach ($model['talant_id'] as $talant_id) {
            $talant = ModelOptions::getTalantName($talant_id);
            $talants[] = $talant;
        }
        $languages = [];
        foreach ($model['language_id'] as $language_id) {
            $language = ModelOptions::getLanguages($language_id);
            $languages[] = $language;
        }
        $gender = ModelOptions::getGenderName($model['gender']);
        if (isset($selectedModels)) {
            $isSelected = in_array($model['model_id'], $selectedModels);
        } else {
            $isSelected = false;
        }
        ?>
        <div class="col-md-2 mb-4">
            <?php if ($isSelected): ?>
                <div class="card model-card" style="background-color:#f8f9fa">
                <?php else: ?>
                    <div class="card model-card">
                    <?php endif; ?>
                    <div class="position-relative">
                        <?php $photoModel = new PhotoModel() ?>
                        <?php require 'core/form/imgInfo.php'; ?>

                        <?php $form = Form::begun('', "post"); ?>
                        <input type="hidden" name="model_id" value="<?php echo $model['model_id']; ?>">
                        <input type="hidden" name="admin_id" value="<?php echo $_SESSION['admin']; ?>">
                        <button type="submit" name="remove_selection"
                            class="btn btn-danger position-absolute bottom-0 end-0 m-2">
                            <i class="bi bi-person-fill-x"></i>
                        </button>

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

    <?php include 'core/form/Modal.php'; ?>

    <script src="/js/models.js"></script>
    <script src="/js/selection.js"></script>