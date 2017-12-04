<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\core\Bootstrap;
use yii\grid\GridView;
use \jakharbek\user\models\UserForm;
use \jakharbek\user\models\User;
use kartik\daterange\DateRangePicker;
use \yii\widgets\Pjax;
?>
<h1><?=Yii::t('jakhar-user','Search Users')?></h1>
<?php
Pjax::begin();
echo GridView::widget([
        'dataProvider' => $adprovider,
        'filterModel' => $model,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute'=>'name',
                    'format' => 'raw',
                    'value' => function($data){
                        return Html::a($data->name,$data->getProfileLink(true));
                    }
                ],
                'login',
                'email',
                [
                    'attribute' => 'phone',
                    'filter' =>  \yii\widgets\MaskedInput::widget([
                        'name'=> 'UserForm[phone]',
                        'mask' => '99-999-99-99',
                        'value' => $user->phone,
                    ]),
                ],
                [
                    'attribute' => 'add_date',
                    'format' => ['date','d.M.Y H:i'],
                    'filter' => DateRangePicker::widget([
                        'model'=>$model,
                        'attribute'=>'add_date',
                        'convertFormat'=>true,
                        'pluginOptions'=>[
                            'timePicker'=>true,
                            'timePickerIncrement'=>30,
                            'locale'=>[
                                'format'=>'Y-m-d h:i:s'
                            ]
                        ]
                    ])
                ],
                [
                    'attribute' => 'status',
                    'filter' => User::$statuses,
                    'format' => 'raw',
                    'value' => function($data){
                        $statuses = User::$statuses;
                        $str = '';
                        if($data->status == User::STATUS_VERIFIED):
                            $str .= '<span class="label label-success">';
                                $str .= $statuses[$data->status];
                            $str .= "</span>";
                        endif;
                        if($data->status == User::STATUS_NOT_VERIFIED):
                            $str .= '<span class="label label-warning">';
                            $str .= $statuses[$data->status];
                            $str .= "</span>";
                        endif;
                        if($data->status == User::STATUS_USER_BLOCKED):
                            $str .= '<span class="label label-danger">';
                            $str .= $statuses[$data->status];
                            $str .= "</span>";
                        endif;
                        return $str;
                    }
                ],
            [
                'attribute' => 'last_action',
                'filter' => User::$onlines,
                'format' => 'raw',
                'value' => function($data){
                    $str = '';
                    if($data->is_online()):
                        $str .= '<span class="label label-success">';
                        $str .= Yii::t('jakhar-user','Online');
                        $str .= "</span>";
                    endif;
                    if(!$data->is_online()):
                        $str .= '<span class="label label-warning">';
                        $str .= $data->last_action();
                        $str .= "</span>";
                    endif;
                    return $str;
                }
            ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $url = yii\helpers\Url::to(['/test/admin/profile/delete']);
                            return Html::a('<span class="delete-grid-element glyphicon glyphicon-trash"></span>', $url, [
                                'title'        => 'delete',
                                'data-query' => 'delete',
                                'data-query-delete-selector' => '[data-key='.$model->id.']',
                                'data-query-method' => 'POST',
                                'data-query-url' => $url,
                                'data-query-confirm' => Yii::t('jakhar-user','Are you sure?'),
                                'data-query-params' => 'uid='.$model->uid,
                            ]);
                        },
                        'view' => function($url,$model){
                            return Html::a('<span class="delete-grid-element glyphicon glyphicon-eye-open"></span>',$model->getProfileLink());
                        },
                        'update' => function($url,$model){
                            $url = \yii\helpers\Url::to(['/test/admin/update/','id' => $model->uid]);
                            return Html::a('<span class="delete-grid-element glyphicon glyphicon-pencil"></span>',$url);
                        }
                    ]
                ],
        ],
]);

Pjax::end();
?>
