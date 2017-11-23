<?php
namespace jakharbek\user\modules\admin\actions\update;

use Yii;
use yii\web\Controller;
use yii\base\Action;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use yii\Helpers\Url;
use yii\Helpers\Html;
use jakharbek\user\Bootstrap;

class PasscodeUpdateAction extends Action{
    public function run(){

        $session = Yii::$app->session;
        //init
        $model =  new UserForm(["scenario" => User::SCENARIO_UPDATE_PASSCODE]);

        //ajax logic
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){Yii::$app->response->format = Response::FORMAT_JSON;return ActiveForm::validate($model);}
        //logic
        if ($model->load(Yii::$app->request->post())) {
            if(($is_validate = $model->validate())):
                $user = User::getByUid(Yii::$app->user->identity->uid);
                $user->setNewPasscode($model->passcode);
                if(($is_save = $user->save()))
                {
                    $session->setFlash('passcode_update',Yii::t('jakhar-user','Passcode is updated'));
                }

            endif;
        }
        return $this->controller->render('passcode',compact('db','model','is_save','is_validate','session'));
    }
}