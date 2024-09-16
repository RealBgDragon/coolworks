<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\core\Session;
use app\models\PhotoModel;
use app\models\SelectModel;

class AdminController extends Controller
{

    public function adminHome()
    {
        $photoModel = new PhotoModel();
        $selectModel = new SelectModel();
        $this->checkIfAdmin();
        $modelsCount = $photoModel->getModelsCount([]);
        $maleModelsCount = $photoModel->getModelsCount(['gender' => 'm']);
        $femaleModelsCount = $photoModel->getModelsCount(['gender' => 'f']);
        $childrenModelsCount = $photoModel->getModelsCount(['age_max' => 18]);
        $limit = 10;
        $info = 'name, add_date';
        $lastModels = $photoModel->getLastModels($info, $limit);
        $topModels = $selectModel->getRandomModels();

        $params = [
            'name' => "Admin BgDragon",
            'modelsCount' => $modelsCount,
            'maleModelsCount' => $maleModelsCount,
            'femaleModelsCount' => $femaleModelsCount,
            'childrenModelsCount' => $childrenModelsCount,
            'topModels' => $topModels,
            'lastModels' => $lastModels
        ];
        $this->setLayout('admin_main');
        return $this->render('admin_home', $params);
    }

}