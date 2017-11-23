<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\user\models\User;
use jakharbek\core\Bootstrap;

echo $session->getFlash('reg_succ');
echo $session->getFlash('email_ver');
echo $session->getFlash('email_ver_delete');
echo $session->getFlash('passcode_ver');
echo $session->getFlash('passcode_ver_delete');
?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'login')->textInput() ?>

            <?= $form->field($model, 'passcode')->passwordInput() ?>

            <?php
            echo $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-12"><div class="col-xs-3">{image}</div><div class="col-xs-9">{input}</div></div></div>',
                'captchaAction' => \yii\helpers\Url::to("/".Bootstrap::$controllers['captcha_controller'][0]."/user")]);
            ?>
            <div class="row col-md-12">
                <?= \yii\Helpers\Html::a(Yii::t("jakhar-user",'Forgot Password'),["/".\jakharbek\user\Bootstrap::$controllers['login_page'][0].'/forgot/']) ?>
            </div>
            <div class="row col-md-12">
                <?= \yii\Helpers\Html::a(Yii::t("jakhar-user",'Registration'),["/".\jakharbek\user\Bootstrap::$controllers['registration_page'][0].'']) ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t("jakhar-user",'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
