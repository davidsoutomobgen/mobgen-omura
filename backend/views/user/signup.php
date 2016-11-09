<?php


/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-create">

    <h1>Create User</h1>

    <!-- p>Please fill out the following fields to signup:</p -->


            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'first_name') ?>
                <?= $form->field($model, 'last_name') ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
                <?php 
                    $items[10] = 'DEVELOPER';
                    $items[11] = 'QA';
                    $items[12] = 'LEAD';

                    echo $form->field($model, 'role_id')->dropDownList(
                        $items,           // Flat array ('id'=>'label')
                        ['prompt'=>'']    // options
                    );
                ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?php
                   // $authItems = ArrayHelper::map($authItems,'name','name');
                 ?>
                <?php //= $form->field($model,'permissions')->checkboxList($authItems,['class'=>'form-inline'])->label(false); ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>

</div>
