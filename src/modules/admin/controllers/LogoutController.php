<?php

namespace jakharbek\user\modules\admin\controllers;

use yii\web\Controller;

/**
 * Default controller for the `test` module
 */
class LogoutController extends Controller
{
    public function actions(){
        return [
            'index' => 'jakharbek\user\actions\logout\LogoutAction',
            'logout' => 'jakharbek\user\actions\logout\LogoutAction',
        ];
    }
}
