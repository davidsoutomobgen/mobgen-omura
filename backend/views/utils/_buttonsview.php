<?php
use yii\helpers\Html;
?>
<p>
    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
    <?= Html::a(Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class'=>'btn btn-warning']); ?>
</p>