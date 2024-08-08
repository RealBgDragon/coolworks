<link rel="stylesheet" href="/css/models.css">
<div class="col-md-auto d-flex justify-content-end mb-4">
    <a href="/wcp/add-models" class="btn btn-primary btn-sm">Add Model <i class="fas fa-plus ml-2"></i></a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <?php use app\core\form\Form;
        use app\core\form\ModelOptions;
        use app\models\PhotoModel;

        ?>
        <button id="toggleFilters" class="btn btn-primary mb-3">Toggle Filters</button>
        <div id="filterSection" style="display: none;">
            <?php $form = Form::begun('', "get"); ?>

            <div class="form-group mr-2">
                <label for="sort">Sort by:</label>
                <select name="sort" id="sort" class="form-control ml-2">
                    <option value="name" <?php echo $currentSort === 'name' ? 'selected' : ''; ?>>Name</option>
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
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mr-2">
                            <label for="eye_color">Eye Color:</label>
                            <div id="eye_color_options" class="custom-select-multiple">
                                <div class="option" data-value="">Any</div>
                                <?php
                                $eyeColors = [
                                    ModelOptions::EYE_COLOR_BLUE,
                                    ModelOptions::EYE_COLOR_GREEN,
                                    ModelOptions::EYE_COLOR_BROWN
                                ];
                                foreach ($eyeColors as $value) {
                                    $selected = in_array($value, $currentFilter['eye_color'] ?? []) ? 'selected' : '';
                                    ?>
                                    <div class="option <?php echo $selected; ?>" data-value="<?php echo $value; ?>">
                                        <?php echo ModelOptions::getEyeColorName($value); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="eye_color[]" id="eye_color_input" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mr-2">
                            <label for="hair_color">Hair Color:</label>
                            <div id="hair_color_options" class="custom-select-multiple">
                                <div class="option" data-value="">Any</div>
                                <?php
                                $hairColors = [
                                    ModelOptions::HAIR_COLOR_BLONDE,
                                    ModelOptions::HAIR_COLOR_BROWN,
                                    ModelOptions::HAIR_COLOR_BLACK
                                ];
                                foreach ($hairColors as $value) {
                                    $selected = in_array($value, $currentFilter['hair_color'] ?? []) ? 'selected' : '';
                                    ?>
                                    <div class="option <?php echo $selected; ?>" data-value="<?php echo $value; ?>">
                                        <?php echo ModelOptions::getHairColorName($value); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="hair_color[]" id="hair_color_input" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i>
                    Apply</button>
                <a href="/wcp/models" class="btn btn-primary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset filters
                </a>
            </div>
            <?php $form::end(); ?>
        </div>
    </div>
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


<div class="row">
    <div class="col-md-12">
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $currentPage == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="/wcp/models?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
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
                                <!-- Images are dynamically added here -->
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

<script src="/js/models.js" async></script> <!-- //!if there are problems remove async -->