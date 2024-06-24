<?php

namespace app\controllers;

use app\core\Controller;


class SelectionController extends Controller
{
    public function selection()
    {
        $this->setLayout('admin_main');
        return $this->render('selection');
    }


}