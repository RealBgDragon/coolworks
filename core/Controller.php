<?php

namespace app\core;

class Controller
{
    public string $layout = 'main';
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $params = [])
    {
        return Application::$app->router->renderView($view, $params);
    }

    public function checkIfAdmin()
    {
        $response = new Response;
        if (!Application::$app->isAdmin()) {

            $session = new Session();
            $session->setFlash('error', 'You must login to see this page');

            $response->redirect('/wcp');
            return;
        }
    }

    public function userMessage($type, $message)
    {
        $session = new Session();
        $session->setFlash("$type", "$message");
    }
}