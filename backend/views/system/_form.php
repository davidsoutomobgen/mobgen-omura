<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\System */
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
    <div class="col-xs-6">
        <?= $form->field($model, 'last_update')->textInput() ?>
    </div>
    <div class="col-xs-6">
        <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>

<div class="btn-footer">
    <?= $this->render('/utils/_buttonsforms', [
        'model' => $model,
    ]); ?>
</div>
<?php ActiveForm::end(); ?>
