<?php
use yii\helpers\Html;
?>
<p>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Saved'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php echo Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer, ['class'=>'btn btn-warning']);?>
</p>