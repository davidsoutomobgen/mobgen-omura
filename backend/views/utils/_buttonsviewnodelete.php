<?php
use yii\helpers\Html;
?>
<p>
    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class'=>'btn btn-warning']); ?>
</p>