<?php
use yii\helpers\Html;
?>
<p id="submit_form" >
    <?php if ($model->isNewRecord) { ?>
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'disabled'=>true ])            ?>
    <?php } else { ?>
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php } ?>
    <?= Html::a( Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-warning']);?>
</p>