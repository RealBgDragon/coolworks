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

            $selectedModelId = $selectModel->model_id;

            if (isset($_POST['save_selection'])) {
                unset($_SESSION['selected_models']);
                if ($selectModel->saveSelection()) {
                    $this->userMessage('success', 'Selection was successfully saved!');
                } else {
                    $this->userMessage('error', 'Selection could not be saved!');
                }
                $response->redirect('/wcp/selection');
                return;
            }

            if (isset($_POST['remove_selection'])) {
                unset($_SESSION['selected_models'][$selectedModelId]);
                if ($selectModel->removeSelection()) {
                    $this->userMessage('success', 'Model was successfully removed!');
                } else {
                    $this->userMessage('error', 'Model could not be removed!');
                }
                $response->redirect('/wcp/selection');
                return;
            }

            if (isset($_POST['delete_selection'])) {
                unset($_SESSION['selected_models']);
                if ($selectModel->deleteSelection()) {
                    $this->userMessage('success', 'Selection was successfully deleted!');
                } else {
                    $this->userMessage('error', 'Selection could not be deleted!');
                }
                $response->redirect('/wcp/selection');
                return;
            }

            if (isset($_POST['transfer_selection'])) {
                unset($_SESSION['selected_models']);
                $selection_name = $_POST['selection_name'];
                if ($selectModel->transferSelection($selection_name)) {
                    $this->userMessage('success', 'Models were successfully transferred!');
                } else {
                    $this->userMessage('error', 'Models could not be transferred!');
                }
                $response->redirect('/wcp/selection');
                return;
            }
        }
        $selection_options = $selectModel->getSelections();

        $selection_name = $_GET['name'] ?? null;
        $selectedModelIds = $selectModel->getSelectedModelIds($selection_name);
        $join = ' JOIN c_models_talants mt ON models.model_id = mt.model_id ';
        $join .= ' JOIN c_models_languages ml ON models.model_id = ml.model_id ';
        $modelsData = $selectModel->getModelsByIds($selectedModelIds, $join);
        $this->setLayout('admin_main');
        return $this->render('selection', [
            'modelsData' => $modelsData,
            'selectionName' => $selection_name,
            'selectionOptions' => $selection_options
        ]);
    }
}
