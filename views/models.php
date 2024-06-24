<div class="col-md-auto d-flex justify-content-end mb-4">
    <a href="/wcp/add-models" class="btn btn-primary btn-sm">Add Model <i class="fas fa-plus ml-2"></i></a>
</div>
<div class="row">
    <?php foreach ($modelsData as $model): ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="/uploads/<?php echo $model['model_id']; ?>/img.png" class="card-img-top img-fluid"
                    alt="Model Image" style="width: 300px; height: 300px;" data-toggle="modal" data-target="#modelModal"
                    data-model-id="<?php echo $model['model_id']; ?>"
                    data-model-name="<?php echo $model['first_name'] . ' ' . $model['last_name']; ?>"
                    data-model-age="<?php echo $model['age']; ?>" onclick="showModelDetails(this)">
                <div class="card-body">
                    <p class="card-text"><?php echo $model['first_name'] . ' ' . $model['last_name'] ?></p>
                    <p class="card-text"><?php echo $model['age'] ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/models.js"></script>