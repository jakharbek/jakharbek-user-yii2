<?php
namespace jakharbek\user\actions\login;

use jakharbek\user\Bootstrap;
use Yii;
use yii\web\Controller;
use yii\base\Action;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use \yii\web\Cookie;

class LoginAction extends Action{

    public $view = '@jakhar/user/views/login/login';

    public function run()
    {
        if(strlen(Bootstrap::$views['login_page']) > 0):
            $this->view = Bootstrap::$views['login_page'];
        endif;
        $session = Yii::$app->session;
        //init
        $model =  new UserForm(["scenario" => User::SCENARIO_LOGIN]);
        $db = new User(["scenario" => User::SCENARIO_LOGIN]);

        //ajax logic
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){Yii::$app->response->format = Response::FORMAT_JSON;return ActiveForm::validate($model);}
        //logic
        if ($model->load(Yii::$app->request->post()))
        {
            if($is_validate = $model->validate())
            {
                $user = $model->getUser();
                $token = new Token(['scenario' => Token::SCENARIO_CREATE]);
                $token->description = "Login";
                $token->type = Token::TYPE_AUTH_USER;
                $token->value = $user->uid;
                if($token->save()):
                    Yii::$app->user->loginByAccessToken($token->token);
                    if(Yii::$app->user->can(Bootstrap::$premission_admin_panel)):
                        $this->controller->redirect(Bootstrap::$redirect_after_login_admin);
                    else:
                        $this->controller->redirect(Bootstrap::$redirect_after_login);
                    endif;
                endif;
            }
            else
            {
                $errors = $model->getErrors();
            }
        }
        if(Yii::$app->request->cookies->has('reg_succ')):
            $session->setFlash("reg_succ",Yii::t('jakhar-user','You have successfully passed the registration, a letter has been sent to your mail for confirmation.'));
            Yii::$app->response->cookies->remove('reg_succ');
        endif;
        if(Yii::$app->request->cookies->has('email_ver')):
            $session->setFlash("email_ver",Yii::t('jakhar-user','You have successfully confirmed your email.').Yii::$app->request->cookies->getValue('email_ver'));
            Yii::$app->response->cookies->remove('email_ver');
        endif;
        if(Yii::$app->request->cookies->has('email_ver_delete')):
            $session->setFlash("email_ver_delete",Yii::t('jakhar-user','You have successfully confirmed that this mail is not your.').Yii::$app->request->cookies->getValue('email_ver'));
            Yii::$app->response->cookies->remove('email_ver_delete');
        endif;
        if(Yii::$app->request->cookies->has('passcode_ver')):
            $session->setFlash("passcode_ver",Yii::t('jakhar-user','You have successfully updated your password.'));
            Yii::$app->response->cookies->remove('passcode_ver');
        endif;
        if(Yii::$app->request->cookies->has('passcode_ver_delete')):
            $session->setFlash("passcode_ver_delete",Yii::t('jakhar-user','You have successfully deleted the password link.'));
            Yii::$app->response->cookies->remove('passcode_ver_delete');
        endif;
        //create params for view
        $params = compact("model",'is_validate','is_save','errors','session');
        //return view;
        return $this->controller->render($this->view,$params);
    }
}