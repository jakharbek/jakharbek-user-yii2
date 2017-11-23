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

class UpdateAction extends Action{
    public function run($id = null){

        $session = Yii::$app->session;
        //init
        $model =  new UserForm(['uid' => $id,"scenario" => User::SCENARIO_UPDATE_ADMIN]);

        //ajax logic
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){Yii::$app->response->format = Response::FORMAT_JSON;return ActiveForm::validate($model);}
        //logic
        if ($model->load(Yii::$app->request->post())) {
            if(($is_validate = $model->validate())):

                $user_id  = Yii::$app->user->identity->uid;
                $user = User::getByUid($user_id);

                if(User::getByUid($id,null)):
                    $user = User::getByUid($id,null);
                endif;

                $user->scenario = User::SCENARIO_UPDATE_ADMIN;
                $user->setAttributes($model->attributes);
                if(($is_save = $user->save()))
                {
                    $session->setFlash('user_updated',Yii::t("jakhar-user",'User is updated'));
                    $model->passcode = null;
                }
            endif;
        }
        return $this->controller->render('update',compact('db','model','is_save','is_validate','session'));
    }
}