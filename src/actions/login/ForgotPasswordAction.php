<?php
namespace jakharbek\user\actions\login;

use Yii;
use yii\web\Controller;
use yii\base\Action;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use \yii\web\Cookie;
use \jakharbek\core\token\models\TokenPasscode;
use jakharbek\user\Bootstrap;

class ForgotPasswordAction extends Action{

    public $view = '@jakhar/user/views/login/forgot';
    public function run()
    {
        if(strlen(Bootstrap::$views['forgot_page']) > 0):
            $this->view = Bootstrap::$views['forgot_page'];
        endif;

        $session = Yii::$app->session;
        //init
        $model = new UserForm(["scenario" => User::SCENARIO_FORGOT_PASSCODE]);
        $db = new User(["scenario" => User::SCENARIO_FORGOT_PASSCODE]);

        //ajax logic
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        //logic
        if ($model->load(Yii::$app->request->post())) {
            if ($is_validate = $model->validate()) {

                $verify_url = ['link' => Bootstrap::$verify_passcode_link,'param' => Bootstrap::$verify_passcode_param];
                $delete_token_url = ['link' => Bootstrap::$verify_passcode_delete_link,'param' => Bootstrap::$verify_passcode_delete_param];
               TokenPasscode::resetPassword($model->email,$verify_url,$delete_token_url);

            }
        }
        //create params for view
        $params = compact("model",'is_validate','session');
        //return view;
        return $this->controller->render($this->view,$params);
    }
}