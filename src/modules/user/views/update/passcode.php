<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\core\Bootstrap;

?>
<h2><?=Yii::t('jakhar-user','Update passcode')?></h2>
<div class="site-update">

    <div class="row">
        <?php if($session->hasFlash('passcode_update')):?>
            <div class="col-lg-12">
                <div class="alert alert-success">
                    <?=$session->getFlash('passcode_update')?>
                </div>
            </div>
        <?php endif;?>
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'passocde-update-form']); ?>

            <?= $form->field($model, 'passcode_old')->passwordInput() ?>

            <?= $form->field($model, 'passcode')->passwordInput() ?>

            <?= $form->field($model, 'passcode_r')->passwordInput() ?>
            <?php
            echo $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-12"><div class="col-xs-3">{image}</div><div class="col-xs-9">{input}</div></div></div>',
                'captchaAction' => \yii\helpers\Url::to("/".Bootstrap::$controllers['captcha_controller'][0]."/user")]);

            ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t("jakhar-user",'Update'), ['class' => 'btn btn-primary', 'name' => 'passcode-update-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
