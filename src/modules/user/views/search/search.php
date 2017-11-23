<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\core\Bootstrap;
?>
<h1><?=Yii::t('jakhar-user','Search Users')?></h1>
<hr />
<div class="row">
    <div class="col-md-12">
        <?php
        $form = ActiveForm::begin(['id' => 'search-form','method' => 'get']);
        echo $form->field($model,'search_text')->textInput();
        ActiveForm::end();

        echo Html::beginForm([],'post');
            echo Html::submitButton('Online',['name' => 'online_user','value' => '1']);
        echo Html::endForm();
        ?>
    </div>
</div>

<?php
echo yii\widgets\ListView::widget([
    'dataProvider' => $adprovider,
    'itemView' => '_list',
    'summary' => false,
]);?>