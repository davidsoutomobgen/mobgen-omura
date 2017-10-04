<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>'; print_r($user); echo '</pre>'; die;
?>

<div class="site-changepassword">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to change password') ?></p>

    <?php $form = ActiveForm::begin([
        'action' =>['/user/update/'.$user->id],
        'id'=>'changepassword-form',
        'options'=>['class'=>'form-horizontal'],
        'fieldConfig'=>[
            'template'=>"{label}\n<div class=\"col-lg-3\">
                        {input}</div>\n<div class=\"col-lg-5\">
                        {error}</div>",
            'labelOptions'=>['class'=>'col-lg-2 control-label'],
        ],
    ]); ?>
    <?= $form->field($modelpass,'oldpass',['inputOptions'=>[
        'placeholder'=>'Old Password'
    ]])->passwordInput() ?>

    <?= $form->field($modelpass,'newpass',['inputOptions'=>[
        'placeholder'=>'New Password'
    ]])->passwordInput() ?>

    <?= $form->field($modelpass,'repeatnewpass',['inputOptions'=>[
        'placeholder'=>'Repeat New Password'
    ]])->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-11">
            <?= Html::submitButton('Change password',[
                'class'=>'btn btn-primary'
            ]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php //echo 'aki22'; die; ?>