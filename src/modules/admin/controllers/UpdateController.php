<?php

namespace jakharbek\user\modules\admin\controllers;

use yii\web\Controller;

/**
 * Default controller for the `test` module
 */
class UpdateController extends Controller
{
    public function actions(){
        return [
            'index' => 'jakharbek\user\modules\admin\actions\update\UpdateAction',
            'update' => 'jakharbek\user\modules\admin\actions\update\UpdateAction',
            'passcode' => 'jakharbek\user\modules\admin\actions\update\PasscodeUpdateAction',
        ];
    }
}
