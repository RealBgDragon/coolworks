<?php

namespace app\controllers;

use app\core\Controller;


class SiteController extends Controller
{
    public function home()
    {
        $params = [
            'name' => "BgDragon"
        ];
        return $this->render('home', $params);
    }

    public function adminHome()
    {
        $params = [
            'name' => "Admin BgDragon"
        ];
        $this->setLayout('admin_main');
        return $this->render('admin_home', $params);
    }
}