<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PermissionsGroups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permissions-groups-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_permission')->textInput() ?>

    <?= $form->field($model, 'id_group')->textInput() ?>

    <?= $form->field($model, 'state')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
