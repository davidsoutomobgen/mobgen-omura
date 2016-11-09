<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BuildsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="builds-search">
    <?php $form = ActiveForm::begin([
        'action' => ['/otaprojects/'.$model->buiProIdFK],
        'method' => 'get',
    ]); ?>

    <?php
    /* @var $searchModel app\models\UserSearch */
    echo $form->field($model, 'searchString', [
        'template' => '<div class="input-group">{input}<span class="input-group-btn">' .
            Html::submitButton('&nbsp;&nbsp;<span class="glyphicon glyphicon-search form-control-feedback"></span>', ['class' => 'btn btn-default']) .
            '</span></div>',
    ])->textInput(['placeholder' => 'Search']);
    ?>

    <?//= $form->field($model, 'buiId') ?>

    <?//= $form->field($model, 'buiName') ?>

    <?//= $form->field($model, 'buiSafename') ?>

    <?//= $form->field($model, 'buiCreated') ?>

    <?//= $form->field($model, 'buiModified') ?>

    <?php ActiveForm::end(); ?>

</div>
