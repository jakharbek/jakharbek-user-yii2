<?php
namespace jakharbek\user\validators\update;

use Yii;
use yii\validators\Validator;
use jakharbek\user\models\User;
use jakharbek\core\security\components\Security;

class PasscodeValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
       if(md5($model->$attribute) != Yii::$app->user->identity->passcode):
           $this->addError($model,$attribute,Yii::t('jakhar-user','Passcode is wrong'));
       endif;
    }
}