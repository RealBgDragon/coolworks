<div class="col-md-auto d-flex justify-content-end mb-4">
    <a href="/wcp/add-models" class="btn btn-primary btn-sm">Add Model <i class="fas fa-plus ml-2"></i></a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <form id="filterForm" class="form-inline">
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
                <label for="age_min">Age:</label>
                <input type="number" name="age_min" id="age_min" class="form-control ml-2" placeholder="Min"
                    value="<?php echo $currentFilter['age_min']; ?>">
                <input type="number" name="age_max" id="age_max" class="form-control ml-2" placeholder="Max"
                    value="<?php echo $currentFilter['age_max']; ?>">
            </div>
            <div class="form-group mr-2">
                <label for="height_min">Height:</label>
                <input type="number" name="height_min" id="height_min" class="form-control ml-2" placeholder="Min"
                    value="<?php echo $currentFilter['height_min']; ?>">
                <input type="number" name="height_max" id="height_max" class="form-control ml-2" placeholder="Max"
                    value="<?php echo $currentFilter['height_max']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Apply</button>
        </form>
    </div>
</div>

<div class="row">
</div>

<div class="modal fade" id="modelModal" tabindex="-1" role="dialog" aria-labelledby="modelModalLabel"
    aria-hidden="true">
</div>

<script src="/js/models.js"></script>