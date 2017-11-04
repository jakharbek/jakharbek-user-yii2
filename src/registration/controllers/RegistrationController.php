<?php
namespace jakharbek\user\registration\controllers;

use Yii;
use yii\web\Controller;

class RegistrationController extends Controller{

    public function actions()
    {
        return [
            //поиск пользавателей уровен администратора
            'index' => 'jakharbek\user\registration\actions\RegistrationAction',
        ];
    }
}
