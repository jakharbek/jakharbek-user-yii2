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
use yii\web\Response;

class ProfileDeleteAction extends Action{
    public function run($uid = null){
        $session = Yii::$app->session;
        //init
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $uid = Yii::$app->request->post('uid');
            echo "ok";
        }
       if(($user = User::getByUid($uid,null))){
            $user->delete();
       }
        if (!Yii::$app->request->isAjax) {
            $this->controller->redirect(Url::to([Bootstrap::$search_link_admin]));
        }
    }
}