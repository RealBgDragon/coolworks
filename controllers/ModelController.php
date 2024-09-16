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
        $eye_colors = isset($eye_colors[0]) ? $eye_colors[0] : '';
        $hair_colors = isset($hair_colors[0]) ? $hair_colors[0] : '';
        $filter = [
            'age_min' => $age_min,
            'age_max' => $age_max,
            'height_min' => $_GET['height_min'] ?? null,
            'height_max' => $_GET['height_max'] ?? null,
            'eye_color' => $eye_colors,
            'hair_color' => $hair_colors,
            'talant' => array_filter($talant),
            'language' => array_filter($language),
            'gender' => array_filter($gender),
        ];

        $page = $_GET['page'] ?? 1;
        $limit = 24;
        $offset = ($page - 1) * $limit;
        $join = 'LEFT JOIN c_models_talants mt ON m.model_id = mt.model_id ';
        $join .= 'LEFT JOIN c_models_languages ml ON m.model_id = ml.model_id ';
        $modelsData = $photoModel->getAllModels($sort, $order, $filter, $limit, $offset, $join);
        $totalModels = $photoModel->countAll($filter);
        $totalPages = ceil($totalModels / $limit);

        $selectedModels = $_SESSION['selected_models'] ?? [];

        $eyeColorOptions = $photoModel->getAllNames('eye_color_id, name', 'eye_colors');
        $hairColorOptions = $photoModel->getAllNames('hair_color_id, name', 'hair_colors');
        $talantOptions = $photoModel->getAllNames('talent_id, name', 'talents');
        $languages = $photoModel->getAllNames('language_id, language', 'c_languages');

        $this->setLayout('admin_main');
        return $this->render('models', [
            'modelsData' => $modelsData,
            'currentSort' => $sort,
            'currentOrder' => $order,
            'currentFilter' => $filter,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'selectedModels' => $selectedModels,
            'eyeColorOptions' => $eyeColorOptions,
            'hairColorOptions' => $hairColorOptions,
            'talantOptions' => $talantOptions,
            'languages' => $languages
        ]);
    }

    public function addModels(Request $request, Response $response)
    {
        $this->checkIfAdmin();
        $photoModel = new PhotoModel();
        $session = new Session();

        $eyeColorOptions = $photoModel->getAllNames('eye_color_id, name', 'eye_colors');
        $hairColorOptions = $photoModel->getAllNames('hair_color_id, name', 'hair_colors');
        $talantOptions = $photoModel->getAllNames('talent_id, name', 'talents');
        $languages = $photoModel->getAllNames('language_id, language', 'c_languages');

        if ($request->isPost()) {
            $photoModel->loadData($request->getBody());

            // Handle eye color and hair color
            $eyeColorOption = isset($request->getBody()['eye_color']) ? (int) $request->getBody()['eye_color'] : 0;
            $hairColorOption = isset($request->getBody()['hair_color']) ? (int) $request->getBody()['hair_color'] : 0;
            /* $talant = isset($request->getBody()['talant']) ? (int) $request->getBody()['talant'] : 0;
            $gender = isset($request->getBody()['gender']) ? (int) $request->getBody()['gender'] : 0; */

            $photoModel->setEyeColor($eyeColorOption);
            $photoModel->setHairColor($hairColorOption);
            /* $photoModel->setTalant($talant);
            $photoModel->setGender($gender); */

            // Create the new model entry in the database
            if ($photoModel->createNew()) {
                $modelId = $photoModel->getId();
                $name = $photoModel->getName();
                $parts = explode(' ', $name);
                $name = strtolower(implode('-', $parts));

                $uploadFileDir = './uploads/' . $name . '-' . $modelId . '/';

                // Create the directory if it doesn't exist
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                // Handle Main Image Upload
                if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['main_image']['tmp_name'];
                    $fileName = $_FILES['main_image']['name'];
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));
                    $allowedFileExtensions = ['jpg', 'gif', 'png'];

                    if (in_array($fileExtension, $allowedFileExtensions)) {
                        // Save the main image as '1.<file extension>'
                        $mainImageFileName = '1.' . $fileExtension;
                        $mainImageDestPath = $uploadFileDir . $mainImageFileName;

                        if (move_uploaded_file($fileTmpPath, $mainImageDestPath)) {
                            // Save the path to the database
                            $photoModel->setMainImage($mainImageDestPath);
                            $photoModel->updateMainImage();
                        } else {
                            $photoModel->addError('error', 'Error moving the main image to the upload directory.');
                        }
                    } else {
                        $photoModel->addError('error', 'Invalid file extension for the main image.');
                    }
                } else {
                    $photoModel->addError('error', 'Error uploading the main image.');
                }

                // Handle Additional Images Upload
                if (isset($_FILES['additional_images']) && !empty($_FILES['additional_images']['name'][0])) {
                    $allowedFileExtensions = ['jpg', 'gif', 'png'];

                    foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
                        $fileName = $_FILES['additional_images']['name'][$key];
                        $fileNameCmps = explode(".", $fileName);
                        $fileExtension = strtolower(end($fileNameCmps));

                        if (in_array($fileExtension, $allowedFileExtensions)) {
                            // Save additional images as '2.jpg', '3.jpg', etc.
                            $additionalImageFileName = ($key + 2) . '.' . $fileExtension;
                            $additionalImageDestPath = $uploadFileDir . $additionalImageFileName;

                            if (!move_uploaded_file($tmp_name, $additionalImageDestPath)) {
                                $photoModel->addError('error', 'Error moving additional image to the upload directory.');
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
                    return $this->render('newModel', [
                        'model' => $photoModel,
                        'eyeColorOptions' => $eyeColorOptions,
                        'hairColorOptions' => $hairColorOptions,
                        'talantOptions' => $talantOptions,
                        'languages' => $languages
                    ]);
                }

                $response->redirect('/wcp/models');
                return;
            } else {
                $photoModel->addError('error', 'Error creating the model.');
            }
        }

        $this->setLayout('admin_main');
        return $this->render('newModel', [
            'model' => $photoModel,
            'eyeColorOptions' => $eyeColorOptions,
            'hairColorOptions' => $hairColorOptions,
            'talantOptions' => $talantOptions,
            'languages' => $languages
        ]);
    }

}