<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\PhotoModel;


class ProjectsController extends Controller
{

    public function projects()
    {
        //$photoModel = new PhotoModel;
        $this->checkIfAdmin();
        //$videourls = $photoModel->getModel();
        $videourls = [
            'https://www.youtube.com/watch?v=GrhD9w5rR4w',
            'https://www.youtube.com/watch?v=XXmwyyKcBLk&t=1866s'
        ];
        $this->setLayout('admin_main');
        return $this->render('projects', ['videourls' => $videourls]);

    }

}