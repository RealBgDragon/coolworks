<div class="col-md-auto d-flex justify-content-end mb-4">
    <a href="/wcp/add-models" class="btn btn-primary btn-sm">Add Model <i class="fas fa-plus ml-2"></i></a>
</div>
<div class="row">
    <?php foreach ($images as $image): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="/<?php echo $image; ?>" class="card-img-top img-fluid" alt="Model Image">
                <div class="card-body">
                    <p class="card-text">Model description or details can go here.</p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>