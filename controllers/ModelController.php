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
        $photoModel = new PhotoModel;
        $this->checkIfAdmin();
        $modelsData = $photoModel->getModel();
        $this->setLayout('admin_main');
        return $this->render('models', ['modelsData' => $modelsData]);

    }

    public function addModels(Request $request, Response $response)
    {
        $this->checkIfAdmin();
        $photoModel = new PhotoModel();
        if ($request->isPost()) {
            $photoModel->loadData($request->getBody());

            if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['image_url']['tmp_name'];
                $fileName = $_FILES['image_url']['name'];
                $fileSize = $_FILES['image_url']['size'];
                $fileType = $_FILES['image_url']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $allowedfileExtensions = ['jpg', 'gif', 'png'];

                if ($photoModel->createNew() && in_array($fileExtension, $allowedfileExtensions)) {
                    $productID = $photoModel->getId();
                    $uploadFileDir = './uploads/' . $productID . '/';
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0777, true);
                    }

                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $photoModel->image_url = $dest_path; // Save the path to the model
                        $this->userMessage('success', 'Model was sucessfuly added');
                        $response->redirect('/wcp/home');
                        return;
                    } else {
                        $this->userMessage('error', 'There was an error moving the uploaded file.');
                    }
                } else {
                    $this->userMessage('error', 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions));
                }
            } else {
                $this->userMessage('error', 'There was an error uploading the file.');
            }
        }
        $this->setLayout('admin_main');
        return $this->render('newModel', [
            'model' => $photoModel
        ]);
    }
}