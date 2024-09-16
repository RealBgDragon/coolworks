<link rel="stylesheet" href="/css/models.css">

<div class="row mb-4">
    <div class="col-md-12">
        <?php use app\core\form\Form;
        use app\core\form\ModelOptions;
        use app\models\PhotoModel;

        ?>

        <div class="col-md-auto d-flex justify-content-start">
            <div style="margin-right: 15px;">
                <button id="toggleFilters" class="btn btn-primary">Toggle Filters</button>
            </div>
            <a href="/wcp/add-models" class="btn btn-primary btn-sm" id="add_model">Add Model&nbsp;<i
                    class="fas fa-plus ml-2"></i></a>
        </div>
        <div id="filterSection" style="display: none;">
            <?php $form = Form::begun('', "get"); ?>
            <!-- Sort -->
            <div class="form-group mr-2">
                <label for="sort">Sort by:</label>
                <select name="sort" id="sort" class="form-control ml-2">
                    <option value="name" <?php echo $currentSort === 'name' ? 'selected' : ''; ?>>Name</option>
                    <option value="age" <?php echo $currentSort === 'age' ? 'selected' : ''; ?>>Age</option>
                    <option value="height" <?php echo $currentSort === 'height' ? 'selected' : ''; ?>>Height</option>
                </select>
            </div>
            <!-- Order -->
            <div class="form-group mr-2">
                <label for="order">Order:</label>
                <select name="order" id="order" class="form-control ml-2">
                    <option value="asc" <?php echo $currentOrder === 'asc' ? 'selected' : ''; ?>>Ascending</option>
                    <option value="desc" <?php echo $currentOrder === 'desc' ? 'selected' : ''; ?>>Descending</option>
                </select>
            </div>
            <!-- Age filter -->
            <div class="form-group mr-2">
                <label for="age_range">Age Range:</label>
                <input type="text" id="age_range" name="age_range" readonly
                    style="border:0; color:#f6931f; font-weight:bold;">
                <div id="age_slider" style="width: 400px; margin: 10px;"></div>
            </div>
            <!-- Height filter -->
            <div class="form-group mr-2">
                <label for="height_min">Height:</label>
                <input type="number" name="height_min" id="height_min" class="form-control ml-2" placeholder="Min"
                    value="<?php echo $currentFilter['height_min']; ?>">
                <input type="number" name="height_max" id="height_max" class="form-control ml-2" placeholder="Max"
                    value="<?php echo $currentFilter['height_max']; ?>">
            </div>
            <div class="multi-select">
                <div class="row">
                    <!-- Eye color filter-->
                    <div class="col-md-6">
                        <div class="form-group mr-2">
                            <label for="eye_color">Eye Color:</label>
                            <div id="eye_color_options" class="custom-select-multiple">
                                <div class="option" data-value="">Any</div>
                                <?php
                                $selectedEyeColors = isset($currentFilter['eye_color']) ? explode(',', $currentFilter['eye_color']) : [];
                                foreach ($eyeColorOptions as $eyeColor) {
                                    $selected = in_array($eyeColor['eye_color_id'], $selectedEyeColors) ? 'selected' : '';
                                    ?>
                                    <div class="option <?php echo $selected; ?>"
                                        data-value="<?php echo $eyeColor['eye_color_id']; ?>">
                                        <?php echo $eyeColor['name']; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="eye_color[]" id="eye_color_input" value="">
                        </div>
                    </div>
                    <!-- Hair color  filter-->
                    <div class="col-md-6">
                        <div class="form-group mr-2">
                            <label for="hair_color">Hair Color:</label>
                            <div id="hair_color_options" class="custom-select-multiple">
                                <div class="option" data-value="">Any</div>
                                <?php
                                // Assuming $currentFilter['hair_color'] is a string like "1,4,7"
                                $selectedHairColors = isset($currentFilter['hair_color']) ? explode(',', $currentFilter['hair_color']) : [];

                                foreach ($hairColorOptions as $hairColor) {
                                    $selected = in_array($hairColor['hair_color_id'], $selectedHairColors) ? 'selected' : '';
                                    ?>
                                    <div class="option <?php echo $selected; ?>"
                                        data-value="<?php echo $hairColor['hair_color_id']; ?>">
                                        <?php echo $hairColor['name']; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="hair_color[]" id="hair_color_input" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="multi-select">
                <div class="row">
                    <!-- Talants filter -->
                    <div class="col-md-6">
                        <div class="form-group mr-2">
                            <label for="talant">Talants:</label>
                            <div id="talant_options" class="custom-select-multiple">
                                <div class="option" data-value="">Any</div>
                                <?php
                                foreach ($talantOptions as $talant) {
                                    $selected = in_array($talant['talent_id'], $currentFilter['talant'] ?? []) ? 'selected' : '';
                                    ?>
                                    <div class="option <?php echo $selected; ?>"
                                        data-value="<?php echo $talant['talent_id']; ?>">
                                        <?php echo $talant['name']; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="talant[]" id="talant_input" value="">
                        </div>
                    </div>
                    <!-- Language filter -->
                    <div class="col-md-6">
                        <div class="form-group mr-2">
                            <label for="language">Language:</label>
                            <div id="language_options" class="custom-select-multiple">
                                <div class="option" data-value="">Any</div>
                                <?php
                                foreach ($languages as $language) {
                                    $selected = in_array($language['language_id'], $currentFilter['language'] ?? []) ? 'selected' : '';
                                    ?>
                                    <div class="option <?php echo $selected; ?>"
                                        data-value="<?php echo $language['language_id']; ?>">
                                        <?php echo $language['language']; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="language[]" id="language_input" value="">
                        </div>
                    </div>
                    <!-- Gender filter -->
                    <div class="col-md-6">
                        <div class="form-group mr-2">
                            <label for="gender">Gender:</label>
                            <div id="gender_options" class="custom-select-multiple">
                                <div class="option" data-value="">Any</div>
                                <?php
                                $genders = [
                                    ModelOptions::GENDER_MALE,
                                    ModelOptions::GENDER_FEMALE,
                                ];
                                foreach ($genders as $value) {
                                    $selected = in_array($value, $currentFilter['gender'] ?? []) ? 'selected' : '';
                                    ?>
                                    <div class="option <?php echo $selected; ?>" data-value="<?php echo $value; ?>">
                                        <?php echo ModelOptions::getGenderName($value); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="gender[]" id="gender_input" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="/wcp/models" class="btn btn-primary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset filters
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i>
                    Apply</button>
            </div>
            <?php $form::end(); ?>
        </div>
    </div>
</div>


<div class="row">
    <?php
    foreach ($modelsData as $model) {
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
                        <?php if ($isSelected): ?>
                            <div class="position-absolute bottom-0 end-0 m-2 btn-container">
                                <button type="button" class="btn btn-success initial-btn">
                                    <i class="bi bi-person-fill-check"></i>
                                </button>
                                <a href="/wcp/selection" class="btn btn-danger hover-btn" style="display: none;">
                                    <i class="bi bi-person-fill-x"></i>
                                </a>
                            </div>

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
                        <p> Talants: <?php echo implode(', ', $talants); ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    $pageRange = 2; // Number of pages to show on either side of the current page
                    $startPage = max(1, $currentPage - $pageRange);
                    $endPage = min($totalPages, $currentPage + $pageRange);
                    $currentFilters = $_GET;

                    // Show "First" page link
                    if ($startPage > 1) {
                        $currentFilters['page'] = 1; // Explicitly set page to 1
                        echo '<li class="page-item"><a class="page-link" href="/wcp/models?' . http_build_query($currentFilters) . '">1</a></li>';
                        if ($startPage > 2) {
                            echo '<li class="page-item"><span class="page-link">...</span></li>';
                        }
                    }


                    // Loop through the pages within the defined range
                    for ($i = $startPage; $i <= $endPage; $i++) {
                        $currentFilters['page'] = $i;
                        echo '<li class="page-item' . ($currentPage == $i ? ' active' : '') . '"><a class="page-link" href="/wcp/models?' . http_build_query($currentFilters) . '">' . $i . '</a></li>';
                    }

                    // Show "Last" page link
                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<li class="page-item"><span class="page-link">...</span></li>';
                        }
                        $currentFilters['page'] = $totalPages;
                        echo '<li class="page-item"><a class="page-link" href="/wcp/models?' . http_build_query($currentFilters) . '">' . $totalPages . '</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>


    <?php include 'core/form/Modal.php'; ?>

    <script src="/js/models.js" async></script>