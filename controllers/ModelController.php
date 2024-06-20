<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\PhotoModel;


class ModelController extends Controller
{

    public function models()
    {
        $this->checkIfAdmin();
        $images = glob('../*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $this->setLayout('admin_main');
        return $this->render('models', ['images' => $images]);

    }

    public function addModels(Request $request, Response $response)
    {
        $this->checkIfAdmin();
        $photoModel = new PhotoModel();
        if ($request->isPost()) {
            $photoModel->loadData($request->getBody());
            if ($photoModel->createNew()) {
                $response->redirect('/wcp/home');
                return;
            }
        }
        $this->setLayout('admin_main');
        return $this->render('newModel', [
            'model' => $photoModel
        ]);
        /* return $this->render('newModel'); */
    }
}