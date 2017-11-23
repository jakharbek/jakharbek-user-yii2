<?php
namespace jakharbek\user\validators\login;

use Yii;
use yii\validators\Validator;
use jakharbek\user\models\User;
use jakharbek\core\security\components\Security;

class LoginValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $login = $model->login;
        $login = strip_tags($model->login);
        $passcode = md5(strip_tags($model->passcode));
        if(!User::hasByLogin($login)):
            $this->addError($model,$attribute,Yii::t('jakhar-user','Login or passcode wrong'));
            return false;
        endif;
        if(User::hasByLogin($login,User::STATUS_USER_BLOCKED)):
            $this->addError($model,$attribute,Yii::t('jakhar-user','User is blocked'));
            return false;
        endif;
        $user = User::getByLogin($login);
        if($passcode !== $user->passcode):
            $this->addError($model,$attribute,Yii::t('jakhar-user','Login or passcode wrong'));
            return false;
        endif;
        if($user->status == User::STATUS_NOT_VERIFIED):
            $this->addError($model,$attribute,Yii::t('jakhar-user','Your profile is not activated'));
        endif;
    }
}