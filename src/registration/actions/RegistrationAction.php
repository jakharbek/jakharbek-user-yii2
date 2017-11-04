<?php

namespace jakharbek\user\registration\actions;

use Yii;
use yii\web\Controller;
use yii\base\Action;

class RegistrationAction extends Action
{
    public $view = '@jakhar/user/registration/views/registration/registration';

    public function run()
    {
        $model =  new \jakharbek\user\registration\models\UserForm(["scenario" => "registration"]);
        $db = new \jakharbek\user\registration\models\User(["scenario" => "registration"]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                $db->attributes = $model->attributes;
                if($db->validate())
                {
                    echo "asdsd";
                    $db->save();
                }
                else{
                    echo "<pre>";
                        print_r($db->getErrors());
                    echo "</pre>";
                }
            }
            else{
                echo "<pre>";
                print_r($model->getErrors());
                echo "</pre>";
            }
        }

        return $this->controller->render($this->view,['model' => $model]);
    }
}