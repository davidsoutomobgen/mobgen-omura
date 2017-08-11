<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>OTA</b>Share</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?php echo Yii::t('app', 'Forgot your password'); ?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="form-group has-feedback">
                <?php echo $form->field($model, 'username', [
                                            'inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control transparent']
                                        ])->textInput()->input('username', ['placeholder' => "Username or email"])->label(false); ?>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="row">
                <div class="text-center"><p> <?php echo Yii::t('app', 'We send a email with your new password'); ?></p></div>
                <div class="col-xs-12 text-center">
                    <input type="submit" name="login-button" value="<?=Yii::t('app', 'Reset password');?>" class="btn btn-primary">
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

