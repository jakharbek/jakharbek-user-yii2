<?php

namespace jakharbek\user\modules\admin\controllers;

use yii\web\Controller;

/**
 * Default controller for the `test` module
 */
class SearchController extends Controller
{
    public function actions(){
        return [
            'index' => 'jakharbek\user\modules\admin\actions\search\SearchAction',
        ];
    }
}
