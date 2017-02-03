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
        <a href="/"><b>OTA</b>Share</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <?= Yii::$app->session->getFlash('error'); ?>
        <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
        }
        ?>
        <p class="login-box-msg">Sign in to start your session</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="form-group has-feedback">
                <?php echo $form->field($model, 'username', [
                                            'inputOptions' => ['class' => 'form-control transparent']
                                        ])->textInput()->input('username', ['placeholder' => "Username"])->label(false); ?>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
               <?php echo $form->field($model, 'password', [
                                            'inputOptions' => ['class' => 'form-control transparent']
                                        ])->textInput()->input('password', ['placeholder' => "Password"])->label(false); ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <input type="submit" name="login-button" value="<?=Yii::t('app', 'Sign In');?>" class="btn btn-primary">
                </div>
            </div>
        <?php ActiveForm::end(); ?>
        <br />
        <div class="row">
            <div class="col-xs-12">
                <div class="text-center">
                    <p>- OR -</p>
                    <a href="/site/forgotpassword">I forgot my password</a>
                    <!-- a href="register.html" class="text-center">Register a new membership</a -->
                </div>

            </div>
        </div>
    </div>
    <!-- /.login-box-body -->
</div>

