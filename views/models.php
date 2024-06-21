<div class="col-md-auto d-flex justify-content-end mb-4">
    <a href="/wcp/add-models" class="btn btn-primary btn-sm">Add Model <i class="fas fa-plus ml-2"></i></a>
</div>
<div class="row">
    <?php foreach ($modelsData as $model): ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <?php $src = $model['model_id']; ?>
                <img src="/<?php echo "./uploads/$src/img.png"; ?>" class="card-img-top img-fluid" alt="Model Image"
                    style="width: 300px; height: 300px;">

                <div class="card-body">
                    <p class="card-text"><?php echo $model['first_name'] . ' ' . $model['last_name'] ?></p>
                    <p class="card-text"><?php echo $model['age'] ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>