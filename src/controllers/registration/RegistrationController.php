<?php
namespace jakharbek\user\controllers\registration;

use jakharbek\user\Bootstrap;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class RegistrationController extends Controller{

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
                        'actions' => ['index','verifyemail','verifyemaildelete'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['verifyemail','verifyemaildelete'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            //поиск пользавателей уровен администратора
            'index' => 'jakharbek\user\actions\registration\RegistrationAction',
            'verifyemail' => 'jakharbek\user\actions\registration\VerifyEmailAction',
            'verifyemaildelete' => 'jakharbek\user\actions\registration\VerifyEmailDeleteAction',

        ];
    }
}
