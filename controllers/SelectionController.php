<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\SelectModel;// Assuming there is a Model class to fetch model data

class SelectionController extends Controller
{
    public function selection(Request $request, Response $response)
    {
        $selectModel = new SelectModel;
        $this->checkIfAdmin();
        if ($request->isPost()) {
            $selectModel->loadData($request->getBody());

            if ($selectModel->removeSelection()) {
                $this->userMessage('success', 'Model was successfully removed');
                $response->redirect('/wcp/selection');
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

        // Get selected model IDs
        $selectedModelIds = $selectModel->getSelectedModelIds();

        // Get detailed info for selected models
        $modelsData = $selectModel->getModelsByIds($selectedModelIds, $sort, $order, $filter);

        $this->setLayout('admin_main');
        return $this->render('selection', [
            'modelsData' => $modelsData,
            'currentSort' => $sort,
            'currentOrder' => $order,
            'currentFilter' => $filter
        ]);
    }

}
