<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <?php
    //echo '<pre>';print_r($model);echo '</pre>';die;
    ?>
    <?= $form->field($model, 'first_name') ?>
    <?= $form->field($model, 'last_name') ?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'email') ?>
    <?php
    echo $form->field($model, 'role_id')->dropDownList(
        Utils::getRoles(),           // Flat array ('id'=>'label')
        ['prompt'=>'']    // options
    );
    ?>
    <?= $form->field($model, 'password')->passwordInput() ?>

</div>
