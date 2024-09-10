<link rel="stylesheet" href="/css/models.css">
<h1 class="mb-4">Dashboard</h1>

<?php
use app\core\form\ModelOptions; ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Models</h5>
                <h2 class="card-text"><?php echo htmlspecialchars($modelsCount) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Male models</h5>
                <h2 class="card-text"><?php echo htmlspecialchars($maleModelsCount) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Female models</h5>
                <h2 class="card-text"><?php echo htmlspecialchars($femaleModelsCount) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Childern models</h5>
                <h2 class="card-text"><?php echo htmlspecialchars($childrenModelsCount) ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                Recent Activities
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($lastModels as $model): ?>
                    <li class="list-group-item">
                        New model added: <?= htmlspecialchars($model['name']) ?> -
                        <?= date('F j, Y, g:i a', strtotime($model['date_added'])); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                Top Models
            </div>
            <div class="card-body">
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Name</th>
                            <th style="width: 20%;">Age</th>
                            <th style="width: 30%;">Talent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topModels as $model):
                            $talant = ModelOptions::getTalantName($model['talant']);
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($model['name']); ?></td>
                                <td><?php echo htmlspecialchars($model['age']); ?></td>
                                <td><?php echo htmlspecialchars($talant); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>