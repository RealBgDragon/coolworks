<?php

namespace app\core;

class Application
{
    public static string $ROOT_DIR;

    public string $userClass;
    public Router $router;
    public Request $request;
    public static Application $app;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?DbModel $user;
    public Controller $controller;


    public function __construct($rootPath, array $config)
    {
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('admin');
        if (!$primaryValue) {
            $primaryValue = $this->session->get('user');
        }
        if ($primaryValue) {
            $userInstance = new $this->userClass();
            $primaryKey = $userInstance->primaryKey();
            $userInstance = new $this->userClass();
            $this->user = $userInstance->findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }

    public function run()
    {
        echo $this->router->resolve();
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(DbModel $user, bool $type)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        if ($type) {
            $this->session->set('admin', $primaryValue);
        } else {
            $this->session->set('user', $primaryValue);
        }

        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
        $this->session->remove('admin');
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    public function isAdmin(): bool
    {
        if ($this->session->get('admin')) {
            return true;

        } else {
            return false;
        }
    }
}