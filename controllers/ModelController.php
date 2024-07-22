<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\PhotoModel;
use app\models\SelectModel;

class ModelController extends Controller
{
    public function models(Request $request, Response $response)
    {
        $photoModel = new PhotoModel();
        $selectModel = new SelectModel();
        $this->checkIfAdmin();

        if ($request->isPost()) {
            $selectModel->loadData($request->getBody());

            if ($selectModel->createNew()) {
                $this->userMessage('success', 'Model was successfully added');
                $response->redirect('/wcp/models');
                return;
            }
        }

        $sort = $_GET['sort'] ?? 'name';
        $order = $_GET['order'] ?? 'asc';
        $age_range = explode(' - ', $_GET['age_range'] ?? '0 - 100');
        $age_min = (int) trim($age_range[0]);
        $age_max = (int) trim($age_range[1]);
        $filter = [
            'age_min' => $age_min,
            'age_max' => $age_max,
            'height_min' => $_GET['height_min'] ?? null,
            'height_max' => $_GET['height_max'] ?? null,
        ];

        $page = $_GET['page'] ?? 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $modelsData = $photoModel->getAll($sort, $order, $filter, $limit, $offset);
        $totalModels = $photoModel->countAll($filter);
        $totalPages = ceil($totalModels / $limit);

        $this->setLayout('admin_main');
        return $this->render('models', [
            'modelsData' => $modelsData,
            'currentSort' => $sort,
            'currentOrder' => $order,
            'currentFilter' => $filter,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function addModels(Request $request, Response $response)
    {
        $this->checkIfAdmin();
        $photoModel = new PhotoModel();

        if ($request->isPost()) {
            $photoModel->loadData($request->getBody());

            // Handle eye color and hair color
            $eyeColorOption = isset($request->getBody()['eye_color']) ? (int) $request->getBody()['eye_color'] : 0;
            $hairColorOption = isset($request->getBody()['hair_color']) ? (int) $request->getBody()['hair_color'] : 0;
            $photoModel->setEyeColor($eyeColorOption);
            $photoModel->setHairColor($hairColorOption);

            // File upload handling
            if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['image_url']['tmp_name'];
                $fileName = $_FILES['image_url']['name'];
                $fileSize = $_FILES['image_url']['size'];
                $fileType = $_FILES['image_url']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $allowedFileExtensions = ['jpg', 'gif', 'png'];

                if ($photoModel->createNew() && in_array($fileExtension, $allowedFileExtensions)) {
                    $productID = $photoModel->getId();
                    $uploadFileDir = './uploads/' . $productID . '/';
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }

                    $newFileName = 'img.' . $fileExtension;
                    $destPath = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $response->redirect('/wcp/models');
                        return;
                    } else {
                        $photoModel->addError('image_url', 'Error moving the file to the upload directory.');
                    }
                } else {
                    $photoModel->addError('image_url', 'Invalid file extension or error creating the model.');
                }
            } else {
                $photoModel->addError('image_url', 'Error uploading the file.');
            }
        }

        $this->setLayout('admin_main');
        return $this->render('newModel', [
            'model' => $photoModel
        ]);
    }
}
