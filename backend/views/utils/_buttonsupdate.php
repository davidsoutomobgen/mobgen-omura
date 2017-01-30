<?php
use yii\helpers\Html;
?>
<p>
    <?= Html::a(Yii::t('app', 'Edit '.$titulo), ['update', 'id' => $id] , ['class' => 'btn btn-primary']) ?>
</p>