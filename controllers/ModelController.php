<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\core\Session;
use app\models\PhotoModel;
use app\models\SelectModel;

class ModelController extends Controller
{
    public function models(Request $request, Response $response)
    {
        $photoModel = new PhotoModel();
        $this->checkIfAdmin();

        if ($request->isPost()) {
            $selectModel = new SelectModel();
            $selectModel->loadData($request->getBody());

            $selectedModelId = $selectModel->model_id;
            $_SESSION['selected_models'][$selectedModelId] = $selectedModelId;

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

        $hair_colors = isset($_GET['hair_color']) ? (array) $_GET['hair_color'] : [];
        $eye_colors = isset($_GET['eye_color']) ? (array) $_GET['eye_color'] : [];
        $talant = isset($_GET['talant']) ? (array) $_GET['talant'] : [];
        $language = isset($_GET['language']) ? (array) $_GET['language'] : [];
        $gender = isset($_GET['gender']) ? (array) $_GET['gender'] : [];

        $filter = [
            'age_min' => $age_min,
            'age_max' => $age_max,
            'height_min' => $_GET['height_min'] ?? null,
            'height_max' => $_GET['height_max'] ?? null,
            'eye_color' => array_filter($eye_colors),
            'hair_color' => array_filter($hair_colors),
            'talant' => array_filter($talant),
            'language' => array_filter($language),
            'gender' => array_filter($gender),
        ];

        $page = $_GET['page'] ?? 1;
        $limit = 24;
        $offset = ($page - 1) * $limit;

        $modelsData = $photoModel->getAllModels($sort, $order, $filter, $limit, $offset);
        $totalModels = $photoModel->countAll($filter);
        $totalPages = ceil($totalModels / $limit);

        $selectedModels = $_SESSION['selected_models'] ?? [];

        $this->setLayout('admin_main');
        return $this->render('models', [
            'modelsData' => $modelsData,
            'currentSort' => $sort,
            'currentOrder' => $order,
            'currentFilter' => $filter,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'selectedModels' => $selectedModels
        ]);
    }

    public function addModels(Request $request, Response $response)
    {
        $this->checkIfAdmin();
        $photoModel = new PhotoModel();
        $session = new Session();

        if ($request->isPost()) {
            $photoModel->loadData($request->getBody());

            // Handle eye color and hair color
            $eyeColorOption = isset($request->getBody()['eye_color']) ? (int) $request->getBody()['eye_color'] : 0;
            $hairColorOption = isset($request->getBody()['hair_color']) ? (int) $request->getBody()['hair_color'] : 0;
            $talant = isset($request->getBody()['talant']) ? (int) $request->getBody()['talant'] : 0;
            $language = isset($request->getBody()['language']) ? $request->getBody()['language'] : 0;
            $gender = isset($request->getBody()['gender']) ? $request->getBody()['gender'] : 0;
            $photoModel->setEyeColor($eyeColorOption);
            $photoModel->setHairColor($hairColorOption);
            $photoModel->setTalant($talant);
            $photoModel->setLanguage($language);
            $photoModel->setGender($gender);

            // Main image upload handling
            if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['main_image']['tmp_name'];
                $fileName = $_FILES['main_image']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $allowedFileExtensions = ['jpg', 'gif', 'png'];

                if ($photoModel->createNew() && in_array($fileExtension, $allowedFileExtensions)) {
                    $modelId = $photoModel->getId();
                    $uploadFileDir = './uploads/' . $modelId . '/';
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }

                    $newFileName = 'img.' . $fileExtension;
                    $destPath = $uploadFileDir . $newFileName;

                    if (!move_uploaded_file($fileTmpPath, $destPath)) {
                        $photoModel->addError('error', 'Error moving the main image to the upload directory.');
                    }
                } else {
                    $photoModel->addError('error', 'Invalid file extension or error creating the model.');
                }
            } else {
                $photoModel->addError('error', 'Error uploading the main image.');
            }

            // Additional images upload handling
            if (isset($_FILES['additional_images']) && !empty($_FILES['additional_images']['name'][0])) {
                $allowedFileExtensions = ['jpg', 'gif', 'png'];

                foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
                    $fileName = $_FILES['additional_images']['name'][$key];
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));

                    if (in_array($fileExtension, $allowedFileExtensions)) {
                        $newFileName = uniqid() . '.' . $fileExtension;
                        $destPath = $uploadFileDir . $newFileName;

                        if (!move_uploaded_file($tmp_name, $destPath)) {
                            $photoModel->addError('error', 'Error moving an additional image to the upload directory.');
                        }
                    } else {
                        $photoModel->addError('error', 'Invalid file extension for additional images.');
                    }
                }
            }
            if ($photoModel->hasError('error')) {
                $msg = $photoModel->getFirstError('error');
                $session->setFlash('error', "$msg");

                $this->setLayout('admin_main');
                return $this->render('newModel', ['model' => $photoModel]);
            }

            $response->redirect('/wcp/models');
            return;
        }

        $this->setLayout('admin_main');
        return $this->render('newModel', [
            'model' => $photoModel
        ]);
    }
}