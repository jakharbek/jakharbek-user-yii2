<?php
namespace jakharbek\user\actions\logout;

use Yii;
use yii\web\Controller;
use yii\base\Action;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use \yii\web\Cookie;

class LogoutAction extends Action{
    public function run()
    {
        Yii::$app->user->identity->logout();
        $this->controller->refresh();
        return $this->controller->goHome();
    }
}