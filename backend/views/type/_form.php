<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Type */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="type-form">
    <div class="row-none">
        <div class="panel panel-default">

            <div class="panel-body">

                <?php $form = ActiveForm::begin(['id' => 'type-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

                <?php //= $form->field($model, 'logo')->textInput(['maxlength' => true]) ?>
                <?php if (!empty($model->logo)) { ?>
                    <div class="form-group field-type-image_logo">
                        <label class="control-label" for="type-image_logo">Logo</label>
                        <?php echo Html::img('@web/'.$model->logo, ['class' => 'img-responsive logo_project']); ?>
                        <div class="help-block"></div>
                    </div>
                <?php } ?>

                <?= $form->field($model, 'image_logo')->fileInput() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
