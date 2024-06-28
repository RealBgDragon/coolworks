<?php use app\core\Application; ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-YWznkfwAOYW2CWjRD9mfzaRJn5C0TuhD7UPIjcleDAWFINQZvLpNd7UHONtVxvyUp}*">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/wcp/home">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/wcp/home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/wcp/models">Models</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/wcp/projects">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/wcp/selection"><i class="fas fa-camera"></i> Selection</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">Back to website</a>
                    </li>
                </ul>
                <?php if (Application::isGuest()): ?>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/wcp">Login</a>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">Welcome
                                <?php echo Application::$app->user->getDisplayName(); ?> (Logout)</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <?php if (Application::$app->session->getFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo Application::$app->session->getFlash('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (Application::$app->session->getFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo Application::$app->session->getFlash('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="content">
            {{content}}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

</body>

</html>