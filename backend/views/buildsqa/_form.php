<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Utils;

/* @var $this yii\web\View */
/* @var $model backend\models\BuildsQa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="builds-qa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(Utils::getQAStatus(),['prompt'=>'Select Option']); ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
