<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\core\Session;
use app\models\PhotoModel;

class AdminController extends Controller
{

    public function adminHome()
    {
        $photoModel = new PhotoModel();
        $this->checkIfAdmin();
        $modelsCount = $photoModel->getModelsCount();
        $limit = 10;
        $info = 'name, date_added';
        $lastModels = $photoModel->getLastModels($info, $limit);

        $params = [
            'name' => "Admin BgDragon",
            'modelsCount' => $modelsCount,
            'lastModels' => $lastModels
        ];
        $this->setLayout('admin_main');
        return $this->render('admin_home', $params);
    }

}