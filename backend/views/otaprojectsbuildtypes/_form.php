<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjectsBuildtypes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ota-projects-buildtypes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_ota_project')->textInput() ?>

    <?= $form->field($model, 'id_ota_buildtypes')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
