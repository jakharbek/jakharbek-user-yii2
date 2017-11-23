<?php

namespace jakharbek\user\modules\user\controllers;

use yii\web\Controller;

/**
 * Default controller for the `test` module
 */
class ProfileController extends Controller
{
    public function actions(){
        return [
            'index' => 'jakharbek\user\modules\user\actions\profile\ProfileAction',
        ];
    }
}
