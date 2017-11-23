<?php
namespace jakharbek\user\modules\admin\actions\profile;

use Yii;
use yii\web\Controller;
use yii\base\Action;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use yii\Helpers\Url;
use yii\Helpers\Html;
use jakharbek\user\Bootstrap;

class ProfileAction extends Action{
    public function run($id = null){
        $session = Yii::$app->session;
        //init
        if($id != null && User::hasByLogin($id)):
            $user = User::getByLogin($id);
            return $this->controller->render('other',compact('session','user'));
        else:
            $user = Yii::$app->user->identity;
            return $this->controller->render('own',compact('session','user'));
        endif;
    }
}