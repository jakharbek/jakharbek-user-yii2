<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\core\Bootstrap;

?>
<h2><?=Yii::t('jakhar-user','Registration')?></h2>
<div class="site-login">

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'name')->textInput() ?>

            <?= $form->field($model, 'login')->textInput() ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '99-999-99-99',
            ]) ?>

            <?= $form->field($model, 'passcode')->passwordInput() ?>
            <?= $form->field($model, 'passcode_r')->passwordInput() ?>
            <?php
            echo $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-12"><div class="col-xs-3">{image}</div><div class="col-xs-9">{input}</div></div></div>',
                'captchaAction' => \yii\helpers\Url::to("/".Bootstrap::$controllers['captcha_controller'][0]."/user")]);

            ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t("jakhar-user",'Registration'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
