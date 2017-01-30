<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BuildsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="builds-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'buiId') ?>

    <?= $form->field($model, 'buiName') ?>

    <?= $form->field($model, 'buiSafename') ?>

    <?= $form->field($model, 'buiCreated') ?>

    <?= $form->field($model, 'buiModified') ?>

    <?php // echo $form->field($model, 'buiTemplate') ?>

    <?php // echo $form->field($model, 'buiFile') ?>

    <?php // echo $form->field($model, 'buiVersion') ?>

    <?php // echo $form->field($model, 'buiBuildNum') ?>

    <?php // echo $form->field($model, 'buiChangeLog') ?>

    <?php // echo $form->field($model, 'buiProIdFK') ?>

    <?php // echo $form->field($model, 'buiCerIdFK') ?>

    <?php // echo $form->field($model, 'buiType') ?>

    <?php // echo $form->field($model, 'buiBuildType') ?>

    <?php // echo $form->field($model, 'buiApple') ?>

    <?php // echo $form->field($model, 'buiSVN') ?>

    <?php // echo $form->field($model, 'buiFeedUrl') ?>

    <?php // echo $form->field($model, 'buiVisibleClient') ?>

    <?php // echo $form->field($model, 'buiDeviceOS') ?>

    <?php // echo $form->field($model, 'buiLimitedUDID') ?>

    <?php // echo $form->field($model, 'buiBundleIdentifier') ?>

    <?php // echo $form->field($model, 'buiHash') ?>

    <?php // echo $form->field($model, 'buiFav') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
