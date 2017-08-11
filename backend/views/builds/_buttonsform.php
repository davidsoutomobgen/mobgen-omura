<?php
use yii\helpers\Html;
?>
<p id="submit_form" >
    <?php if ($model->isNewRecord) { ?>
        <?php echo Html::submitButton(Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'disabled'=>true ])            ?>
    <?php } else { ?>
        <?php echo Html::submitButton(Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php } ?>
    <?php echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->buiId], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
    <?php echo Html::a( Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-warning']);?>
</p>
