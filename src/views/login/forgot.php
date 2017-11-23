<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\user\models\User;
use jakharbek\core\Bootstrap;
?>
<div class="site-login">

    <div class="row">
        <div class="col-lg-5">
            <?php if($is_validate):?>
             <div class="alert alert-success">
                 <?=Yii::t("jakhar-user",'On your email sent passcode')?>
             </div>
            <?php endif;?>
            <?php $form = ActiveForm::begin(['id' => 'forgot-form']); ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <?php
            echo $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-12"><div class="col-xs-3">{image}</div><div class="col-xs-9">{input}</div></div></div>',
                'captchaAction' => \yii\helpers\Url::to("/".Bootstrap::$controllers['captcha_controller'][0]."/user")]);

            ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t("jakhar-user",'Send'), ['class' => 'btn btn-primary', 'name' => 'forgot-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
