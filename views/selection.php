<?php use app\core\form\Form;
use app\core\form\ModelOptions;

?>
<link rel="stylesheet" href="/css/models.css">
<div class="col-md-auto d-flex justify-content-start mb-4">
    <?php $last = '';
    foreach ($selectionOptions as $selection) {
        if ($selection === $last) {
            continue;
        }
        $last = $selection;
        ?>
        <div class="form-group">
            <a href="selection?selection_name=<?php echo $selection ?>"><?php echo $selection ?> </a>
        </div>
    <?php } ?>
</div>
<div class="col-md-auto d-flex justify-content-end mb-4">
    <?php $form = Form::begun('', "post"); ?>
    <div class="form-group">
        <label for="selection_name">Selection Name:</label>
        <input type="text" class="form-control" id="selection_name" name="selection_name" required>
    </div>
    <button type="submit" name="save_selection" class="btn btn-primary btn-sm">Save selection <i
            class="fas fa-plus ml-2"></i></button>
    <?php $form::end(); ?>
</div>
<div class="row mb-4">
    <div class="col-md-12">
        <?php
        $form = Form::begun('', "get"); ?>
        <div class="form-group mr-2">
            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" class="form-control ml-2">
                <option value="name" <?php echo $currentSort === 'name' ? 'selected' : ''; ?> use app\core\form\Form;>
                    Name</option>
                <option value="age" <?php echo $currentSort === 'age' ? 'selected' : ''; ?>>Age</option>
                <option value="height" <?php echo $currentSort === 'height' ? 'selected' : ''; ?>>Height</option>
            </select>
        </div>
        <div class="form-group mr-2">
            <label for="order">Order:</label>
            <select name="order" id="order" class="form-control ml-2">
                <option value="asc" <?php echo $currentOrder === 'asc' ? 'selected' : ''; ?>>Ascending</option>
                <option value="desc" <?php echo $currentOrder === 'desc' ? 'selected' : ''; ?>>Descending</option>
            </select>
        </div>

        <div class="form-group mr-2">
            <label for="age_range">Age Range:</label>
            <input type="text" id="age_range" name="age_range" readonly
                style="border:0; color:#f6931f; font-weight:bold;">
            <div id="age_slider" style="width: 400px; margin: 10px;"></div>
        </div>

        <div class="form-group mr-2">
            <label for="height_min">Height:</label>
            <input type="number" name="height_min" id="height_min" class="form-control ml-2" placeholder="Min"
                value="<?php echo $currentFilter['height_min']; ?>">
            <input type="number" name="height_max" id="height_max" class="form-control ml-2" placeholder="Max"
                value="<?php echo $currentFilter['height_max']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Apply</button>
        <?php $form::end(); ?>
    </div>
</div>

<div class="row">
    <?php foreach ($modelsData as $model) {
        $eyeColor = ModelOptions::getEyeColorName($model['eye_color']);
        $hairColor = ModelOptions::getHairColorName($model['hair_color']);
        ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="/uploads/<?php echo $model['model_id']; ?>/img.png" class="card-img-top img-fluid"
                    alt="Model Image" style="width: 300px; height: 300px;" data-toggle="modal" data-target="#modelModal"
                    data-model-id="<?php echo $model['model_id']; ?>" data-model-name="<?php echo $model['name']; ?>"
                    data-model-age="<?php echo $model['age']; ?>" data-model-height="<?php echo $model['height']; ?>"
                    data-model-weight="<?php echo $model['weight']; ?>"
                    data-model-birthday="<?php echo $model['birthday']; ?>"
                    data-model-phone="<?php echo $model['phone']; ?>" data-eye-color="<?php echo $eyeColor; ?>"
                    data-hair-color="<?php echo $hairColor; ?>" onclick="showModelDetails(this)">

                <div class="card-body">
                    <?php $form = Form::begun('', "post"); ?>
                    <p class="card-text"><?php echo $model['name'] ?></p>
                    <p class="card-text"><?php echo $model['height']; ?></p>
                    <p class="card-text"><?php echo $model['age'] ?></p>
                    <input type="text" name="model_id" style="display:none;" value="<?php echo $model['model_id'] ?>">
                    <input type="text" name="admin_id" style="display:none;" value="<?php echo $_SESSION['admin'] ?>">
                    <button type="submit" class="btn btn-primary">Deselect model <i class="fas fa-minus ml-2"></i></button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modelModalBody">
                <div class="row">
                    <div class="col-md-6">
                        <img id="modalModelImage" src="" class="img-fluid" alt="Model Image">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="/js/models.js"></script>