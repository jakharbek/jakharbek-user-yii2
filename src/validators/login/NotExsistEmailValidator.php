<?php
namespace jakharbek\user\validators\login;

use yii\validators\Validator;
use jakharbek\user\models\User;
use jakharbek\core\security\components\Security;
use Yii;

class NotExsistEmailValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if(!User::hasByEmail($model->email)){
            $this->addError($model,$attribute,Yii::t('jakhar-user','You input wrong email address'));
        }
    }
}