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
        <a href="../../index2.html"><b>OTA</b>Share</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="form-group has-feedback">
                <?php echo $form->field($model, 'username', [
                                            'inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control transparent']
                                        ])->textInput()->input('username', ['placeholder' => "Username"])->label(false); ?>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
               <?php echo $form->field($model, 'password', [
                                            'inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control transparent']
                                        ])->textInput()->input('password', ['placeholder' => "Password"])->label(false); ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <br />
            <div class="row">
                <!--
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label class="">
                            <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> Remember Me
                        </label>
                    </div>
                </div>
                -->
                <!-- /.col -->
                <div class="col-xs-12 text-center">
                    <input type="submit" name="login-button" value="<?=Yii::t('app', 'Sign In');?>" class="btn btn-primary">
                </div>
                <!-- /.col -->
            </div>
        <?php ActiveForm::end(); ?>
        <!--
            <div class="text-center"><p>- OR -</p></div>
            <a href="#">I forgot my password</a><br>
            <a href="register.html" class="text-center">Register a new membership</a>
         -->
    </div>
    <!-- /.login-box-body -->
</div>

<!-- jQuery 2.2.3 -->
<!-- <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script> -->
<!-- Bootstrap 3.3.6 -->
<!-- <script src="../../bootstrap/js/bootstrap.min.js"></script> -->
<!-- iCheck -->
<!--
<script src="../../plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
-->
