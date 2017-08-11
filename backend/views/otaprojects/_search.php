<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjectsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ota-projects-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'safename') ?>

    <?= $form->field($model, 'proCreated') ?>

    <?= $form->field($model, 'proModified') ?>

    <?php // echo $form->field($model, 'id_project') ?>

    <?php // echo $form->field($model, 'id_ota_template') ?>

    <?php // echo $form->field($model, 'proHash') ?>

    <?php // echo $form->field($model, 'proAPIKey') ?>

    <?php // echo $form->field($model, 'proAPIBuildKey') ?>

    <?php // echo $form->field($model, 'proBuildTypes') ?>

    <?php // echo $form->field($model, 'default_notify_email') ?>

    <?php // echo $form->field($model, 'proDevUrl1') ?>

    <?php // echo $form->field($model, 'proDevUrl2') ?>

    <?php // echo $form->field($model, 'proDevUrl3') ?>

    <?php // echo $form->field($model, 'proDevUrl4') ?>

    <?php // echo $form->field($model, 'proAltUrl1') ?>

    <?php // echo $form->field($model, 'proAltUrl2') ?>

    <?php // echo $form->field($model, 'proAltUrl3') ?>

    <?php // echo $form->field($model, 'proAltUrl4') ?>

    <?php // echo $form->field($model, 'proProdUrl1') ?>

    <?php // echo $form->field($model, 'proProdUrl2') ?>

    <?php // echo $form->field($model, 'proProdUrl3') ?>

    <?php // echo $form->field($model, 'proProdUrl4') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
