<?php

namespace jakharbek\user;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface{
    public function bootstrap($app)
    {
        Yii::setAlias('@jakhar/user', '@vendor/jakharbek/jakharbek-user/src');
        $app->params['yii.migrations'][] = '@vendor/jakharbek/jakharbek-user/src/migrations';
    }
}