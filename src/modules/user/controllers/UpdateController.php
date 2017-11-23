<?php

namespace jakharbek\user\modules\user\controllers;

use yii\web\Controller;

/**
 * Default controller for the `test` module
 */
class UpdateController extends Controller
{
    public function actions(){
        return [
            'index' => 'jakharbek\user\modules\user\actions\update\UpdateAction',
            'update' => 'jakharbek\user\modules\user\actions\update\UpdateAction',
            'passcode' => 'jakharbek\user\modules\user\actions\update\PasscodeUpdateAction',
        ];
    }
}
