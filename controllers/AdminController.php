<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Session;

class AdminController extends Controller
{

    public function adminHome()
    {
        if (!Application::$app->isAdmin()) {
            die();
        }
        $params = [
            'name' => "Admin BgDragon"
        ];
        $this->setLayout('admin_main');
        return $this->render('admin_home', $params);
    }

}