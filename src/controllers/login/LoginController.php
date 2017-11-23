<?php
namespace jakharbek\user\controllers\login;
use jakharbek\user\Bootstrap;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class LoginController extends Controller{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function($rule,$action){
                            if(Yii::$app->user->can(Bootstrap::$premission_admin_panel)):
                                $this->redirect(Bootstrap::$redirect_after_login_admin);
                            else:
                                $this->redirect(Bootstrap::$redirect_after_login);
                            endif;
                            return true;
                        },
                    ],
                    [
                        'actions' => ['index','forgot','verifypasscode','verifypasscodedelete'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'index' => 'jakharbek\user\actions\login\LoginAction',
            'forgot' => 'jakharbek\user\actions\login\ForgotPasswordAction',
            'verifypasscode' => 'jakharbek\user\actions\login\VerifyNewPasscodeAction',
            'verifypasscodedelete' => 'jakharbek\user\actions\login\VerifyNewPasscodeDeleteAction',
        ];
    }
}