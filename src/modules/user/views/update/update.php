<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\core\Bootstrap;

?>
<h2><?=Yii::t('jakhar-user','Update your account')?></h2>
<div class="site-update">

    <div class="row">
        <?php if($session->hasFlash('user_updated')):?>
        <div class="col-lg-12">
            <div class="alert alert-success">
                <?=$session->getFlash('user_updated')?>
            </div>
        </div>
        <?php endif;?>
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'update-form']); ?>

            <?= $form->field($model, 'name')->textInput() ?>

            <?= $form->field($model, 'login')->textInput() ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '99-999-99-99',
            ]) ?>

            <?= $form->field($model, 'passcode')->passwordInput() ?>
            <?php
            echo $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-12"><div class="col-xs-3">{image}</div><div class="col-xs-9">{input}</div></div></div>',
                'captchaAction' => \yii\helpers\Url::to("/".Bootstrap::$controllers['captcha_controller'][0]."/user")]);

            ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t("jakhar-user",'Update'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
