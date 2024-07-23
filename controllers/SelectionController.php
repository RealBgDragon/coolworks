<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\SelectModel;

class SelectionController extends Controller
{
    public function selection(Request $request, Response $response)
    {
        $selectModel = new SelectModel();
        $this->checkIfAdmin();

        if ($request->isPost()) {
            $selectModel->loadData($request->getBody());

            if (isset($_POST['save_selection'])) {
                if ($selectModel->saveSelection()) {
                    $this->userMessage('success', 'Selection was successfully saved');
                } else {
                    $this->userMessage('error', 'Selection could not be saved');
                }
                $response->redirect('/wcp/selection');
                return;
            }

            if (isset($_POST['remove_selection'])) {
                if ($selectModel->removeSelection()) {
                    $this->userMessage('success', 'Model was successfully removed');
                } else {
                    $this->userMessage('error', 'Model could not be removed');
                }
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
        $selection_options = $selectModel->getSelections();
        $selection_name = $_GET['name'] ?? null;
        $selectedModelIds = $selectModel->getSelectedModelIds($selection_name);
        $modelsData = $selectModel->getModelsByIds($selectedModelIds, $sort, $order, $filter);

        $this->setLayout('admin_main');
        return $this->render('selection', [
            'modelsData' => $modelsData,
            'currentSort' => $sort,
            'currentOrder' => $order,
            'currentFilter' => $filter,
            'selectionName' => $selection_name,
            'selectionOptions' => $selection_options
        ]);
    }
}
