<?php
require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\ModelController;
use app\controllers\SiteController;
use app\core\Application;
use app\controllers\AuthController;
use app\controllers\AdminController;
use app\models\User;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$config = [
    'userClass' => User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];


$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/wcp', [AuthController::class, 'admin']);
$app->router->post('/wcp', [AuthController::class, 'admin']);

$app->router->get('/wcp/home', [AdminController::class, 'adminHome']);

$app->router->get('/wcp/models', [ModelController::class, 'models']);

$app->router->get('/wcp/add-models', [ModelController::class, 'addModels']);
$app->router->post('/wcp/add-models', [ModelController::class, 'addModels']);

$app->run();