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

    public function uploadFile()
    {
        $fileName = $_FILES["imageFile"]["name"];

        if ($_FILES["imageFile"]["size"] > 0) {
            $fileName = $_FILES["imageFile"]["name"];

            $uploadDirectory = "C:/xampp/htdocs/coolworks/website_images/";
            $fileName = uniqid() . "-" . basename($_FILES['imageFile']['name']);
            $uploadPath = $uploadDirectory . $fileName;

            move_uploaded_file($_FILES['imageFile']['tmp_name'], $uploadPath);

        }
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