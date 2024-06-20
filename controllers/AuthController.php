<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

class AuthController extends Controller
{
    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        $user = new User();
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            $user->setAdmin(false);
            if ($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/');
                return;
            }
        }
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }

    public function admin(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        $user = new User();
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            $user->setAdmin(true);
            if ($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/wcp/home');
                return;
            }
        }
        $this->setLayout('admin_main');
        return $this->render('admin_login', [
            'model' => $loginForm
        ]);
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $url = $_SERVER['HTTP_REFERER'];
        $response->redirect($url);
    }
}