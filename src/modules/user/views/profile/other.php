<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\core\Bootstrap;
?>

<h2><?=Yii::t('jakhar-user','Profile')?></h2>
<div class="row col-md-6">

<table class="table table-strips">
    <tr>
        <td><?=Yii::t('jakhar-user','Name')?></td>
        <td><?=$user->name?></td>
    </tr>
    <tr>
        <td><?=Yii::t('jakhar-user','Username (Login)')?></td>
        <td><?=$user->login?></td>
    </tr>
    <tr>
        <td><?=Yii::t('jakhar-user','Phone')?></td>
        <td><?=$user->phone?></td>
    </tr>

</table>

</div>