<?php
namespace jakharbek\user\actions\login;

use jakharbek\core\token\models\TokenPasscode;
use jakharbek\user\Bootstrap;
use Yii;
use yii\web\Controller;
use yii\base\Action;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use \jakharbek\core\token\models\TokenEmail;
use \yii\web\Cookie;

class VerifyNewPasscodeDeleteAction extends Action{
    public function run($verifycode = null)
    {
        if($verifycode == null){return;}
        if($token = TokenPasscode::verifyPasscodeDelete($verifycode)):
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'passcode_ver_delete',
                'value' => $token->user_uid
            ]));
        endif;
        return $this->controller->redirect(Bootstrap::$redirect_after_login);
    }
}