<?php

namespace jakharbek\user\modules\user;

use yii\web\Controller;
use Yii;
use jakharbek\user\Bootstrap;
use yii\filters\AccessControl;
/**
 * test module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'jakharbek\user\modules\user\controllers';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->layout = Bootstrap::$layout_user;
        // custom initialization code goes here
    }
}
