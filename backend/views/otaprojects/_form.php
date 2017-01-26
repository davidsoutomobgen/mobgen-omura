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

<?php $form = ActiveForm::begin(); ?>
<div class="btn-header">
    <?= $this->render('/utils/_buttonsforms', [
        'model' => $model,
    ]); ?>
</div>
<div class="clear"></div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo Yii::t('app', 'General information');?></h3>
    </div>

    <?php if ($model->isNewRecord) { ?>
        <?php //= $this->render('_warning'); ?>
    <?php } ?>

    <div class="col-xs-12">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-12">
        <?= $form->field($model, 'id_ota_template')->dropDownList(Utils::getTemplate(),['prompt'=>'Select Option']); ?>
    </div>
    <div class="col-xs-12">
        <?php
        /*
        echo $form->field($model, 'proBuildTypes', [
            'template' => "{label}\n{hint}\n{input}\n{error}"
        ])->textInput()->hint('<p><small>Old version , only for check if the new version is correct.</small></p>')->label('PrBuildTypes');
        */
        ?>

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
                'options' => ['placeholder' => Yii::t('app', 'Select Build Type ...'), 'multiple' => true],
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
    </div>
    <div class="col-xs-12">
        <?= $form->field($model, 'default_notify_email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clear"></div>
</div>
<div class="btn-footer">
    <?= $this->render('/utils/_buttonsforms', [
        'model' => $model,
    ]); ?>
</div>
<?php ActiveForm::end(); ?>

