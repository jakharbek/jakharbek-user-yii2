<?php
namespace jakharbek\user\actions\registration;

use jakharbek\user\Bootstrap;
use Yii;
use yii\web\Controller;
use yii\base\Action;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use \jakharbek\core\token\models\TokenEmail;
use \yii\web\Cookie;

class VerifyEmailDeleteAction extends Action{
    public function run($verifycode = null)
    {
        if($verifycode == null){return;}
        if($token = TokenEmail::verifyEmailDelete($verifycode)):
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'email_ver_delete',
                'value' => $token->value
            ]));
        endif;
        return $this->controller->redirect(Bootstrap::$redirect_after_login);
    }
}