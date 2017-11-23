<?php

namespace jakharbek\user\actions\registration;

use Yii;
use yii\web\Controller;
use yii\base\Action;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use yii\Helpers\Url;
use yii\Helpers\Html;
use jakharbek\user\Bootstrap;

class RegistrationAction extends Action
{
    public $view = '@jakhar/user/views/registration/registration';

    public function run()
    {
        if(strlen(Bootstrap::$views['registration_page']) > 0):
            $this->view = Bootstrap::$views['registration_page'];
        endif;

        $session = Yii::$app->session;
        $cookies = Yii::$app->request->cookies;
        //init
        $model =  new UserForm(["scenario" => User::SCENARIO_REGISTRATION]);
        $db = new User(["scenario" => User::SCENARIO_REGISTRATION]);

        //ajax logic
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){Yii::$app->response->format = Response::FORMAT_JSON;return ActiveForm::validate($model);}
        //logic
        if ($model->load(Yii::$app->request->post()))
        {
            if($is_validate = $model->validate())
            {
                $db->attributes = $model->attributes;
                if($is_save = $db->save())
                {
                    Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'reg_succ','value' => 1]));
                    $this->controller->redirect(['/login']);
                }
                else
                {
                    $errors = $db->getErrors();
                }
            }
            else
            {
                $errors = $model->getErrors();
            }

        }
        //create params for view
        $params = compact("model",'is_validate','is_save','errors','session');
        //return view;
        return $this->controller->render($this->view,$params);
    }
}