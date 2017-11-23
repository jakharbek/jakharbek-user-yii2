<?php

namespace jakharbek\user\modules\admin;
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
    public $controllerNamespace = 'jakharbek\user\modules\admin\controllers';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Bootstrap::$premission_admin_panel],
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

        $this->layout = Bootstrap::$layout_admin;
        // custom initialization code goes here
    }
}
