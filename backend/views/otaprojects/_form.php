<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Utils;
use backend\models\OtaBuildTypes;

/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjects */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ota-projects-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <!--
    <?= $form->field($model, 'safename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proCreated')->textInput() ?>

    <?= $form->field($model, 'proModified')->textInput() ?>
    -->

    <?php //=$form->field($model, 'id_project')->hint('This option is not available for this version')->textInput() ?>

    <?php // = $form->field($model, 'id_ota_template')->textInput() ?>

    <?= $form->field($model, 'id_ota_template')->dropDownList(Utils::getTemplate(),['prompt'=>'Select Option']); ?>

    <!--
    <?= $form->field($model, 'proHash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proAPIKey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proAPIBuildKey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proBuildTypes')->textInput(['maxlength' => true]) ?>
    -->

    <?= $form->field($model, 'proBuildTypes', [
        'template' => "{label}\n{hint}\n{input}\n{error}"
    ])->textInput()->hint('<p><small>Old version , only for check if the new version is correct.</small></p>')->label('PrBuildTypes') ?>


    <?php
    if ($value == -1) $error_class = 'has-error';
    else $error_class = '';
    ?>
    <div class="form-group field-proBuildTypes required <?php echo $error_class;?> ">
        <label class="control-label" for="proBuildTypes">Pro Build Types</label>
        <div class="hint-block"><p class=""><small><?=Yii::t('app', 'You can add new build types automatically');?></small></p></div>
        <?php
        echo Select2::widget([
            'name' => 'proBuildType',
            'value' => $value,
            'data' => $ota_buildtypes,
            'size' => Select2::SMALL,
            'options' => ['placeholder' => 'Select Build Type ...', 'multiple' => true],
            'pluginOptions' => [
                'allowClear' => true,
                'tags' => true,
            ],
        ]);
        ?>
        <?php if ($value == -1) { ?>
            <div class="help-block">BuildTypes cannot be blank.</div>
        <?php } ?>
    </div>

    <?= $form->field($model, 'default_notify_email')->textInput(['maxlength' => true]) ?>

    <!--
    <?= $form->field($model, 'proDevUrl1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proDevUrl2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proDevUrl3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proDevUrl4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proAltUrl1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proAltUrl2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proAltUrl3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proAltUrl4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proProdUrl1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proProdUrl2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proProdUrl3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proProdUrl4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>
    -->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   	<?= Html::a( Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-warning']);?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
