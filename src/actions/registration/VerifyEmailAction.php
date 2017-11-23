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

class VerifyEmailAction extends Action{
    public function run($verifycode = null)
    {
        if($verifycode == null){return;}
        if($token = TokenEmail::verifyEmail($verifycode)):
            $email = $token->value;
            if(Yii::$app->user->isGuest):
                $user = User::findOne(['email' => $email]);
                $user->verifyEmail();
                $user->save();
            else:
                if($token->user_uid == Yii::$app->user->identity->uid):
                    $user = User::getByUid(Yii::$app->user->identity->uid);
                    $user->email = $email;
                    $user->save();
                endif;
            endif;
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'email_ver',
                'value' => $token->value
            ]));
        endif;
        return $this->controller->redirect(Bootstrap::$redirect_after_login);
    }
}