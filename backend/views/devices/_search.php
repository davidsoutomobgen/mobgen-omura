<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DevicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="devices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'label_1') ?>

    <?= $form->field($model, 'label_2') ?>

    <?= $form->field($model, 'brand') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'technical_details') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'office') ?>

    <?php // echo $form->field($model, 'used_by') ?>

    <?php // echo $form->field($model, 'information_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'delete') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
