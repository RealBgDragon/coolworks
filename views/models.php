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
                    <!-- Hair color  filter-->
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
            <div class="multi-select">
                <div class="row">
                    <!-- Talants filter -->
                    <div class="col-md-6">
                        <div class="form-group mr-2">
                            <label for="talant">Talants:</label>
                            <div id="talant_options" class="custom-select-multiple">
                                <div class="option" data-value="">Any</div>
                                <?php
                                $talants = [
                                    ModelOptions::TALANT_ACTOR,
                                    ModelOptions::TALANT_PHOTO_MODEL,
                                ];
                                foreach ($talants as $value) {
                                    $selected = in_array($value, $currentFilter['talant'] ?? []) ? 'selected' : '';
                                    ?>
                                    <div class="option <?php echo $selected; ?>" data-value="<?php echo $value; ?>">
                                        <?php echo ModelOptions::getTalantName($value); ?>
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
                                $languages = [
                                    ModelOptions::LANGUAGE_BULGARIAN,
                                    ModelOptions::LANGUAGE_ENGLISH,
                                ];
                                foreach ($languages as $value) {
                                    $selected = in_array($value, $currentFilter['language'] ?? []) ? 'selected' : '';
                                    ?>
                                    <div class="option <?php echo $selected; ?>" data-value="<?php echo $value; ?>">
                                        <?php echo ModelOptions::getLanguages($value); ?>
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
                                    ModelOptions::GENDER_CHILD,
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


<?php include 'core/form/ModelCard.php'; ?>

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

<?php include 'core/form/Modal.php'; ?>

<script src="/js/models.js" async></script>