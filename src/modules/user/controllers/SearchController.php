<?php

namespace jakharbek\user\modules\user\controllers;

use yii\web\Controller;

/**
 * Default controller for the `test` module
 */
class SearchController extends Controller
{
    public function actions(){
        return [
            'index' => 'jakharbek\user\modules\user\actions\search\SearchAction',
        ];
    }
}
