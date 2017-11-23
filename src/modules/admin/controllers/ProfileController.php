<?php

namespace jakharbek\user\modules\admin\controllers;

use yii\web\Controller;

/**
 * Default controller for the `test` module
 */
class ProfileController extends Controller
{
    public function actions(){
        return [
            'index' => 'jakharbek\user\modules\admin\actions\profile\ProfileAction',
            'delete' => 'jakharbek\user\modules\admin\actions\profile\ProfileDeleteAction',
        ];
    }
}
