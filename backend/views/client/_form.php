<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\switchinput\SwitchInput;
use common\models\User;
use backend\models\Utils;
use backend\models\SignupForm;
use backend\models\Project;

/* @var $this yii\web\View */
/* @var $model backend\models\Client */
/* @var $form yii\widgets\ActiveForm */

$userIdRole = User::getUserIdRole();

if ($model->user0 == null) {
    $user = new SignupForm();
    $userNotExist = true;
} else {
    $user = $model->user0;
    $userNotExist = false;
}
?>

<?php $form = ActiveForm::begin(['id'=>$model->formName(), 'options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="btn-header">
    <?= $this->render('/utils/_buttonsforms', [
        'model' => $model,
    ]); ?>
</div>
<div class="clear"></div>
<?php
if (Yii::$app->getSession()->hasFlash('success')) {
    echo '<div class="alert alert-success">'.Yii::$app->getSession()->getFlash('success').'</div>';
}
if (Yii::$app->getSession()->hasFlash('error')) {
    echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
}

?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo Yii::t('app', 'General information');?></h3>
    </div>

    <div class="col-xs-6">
        <?php
        $size = '';
        $title = '';
        $image = '';


        if (!$model->isNewRecord) {
            //$path_file = Yii::getAlias('@webroot') . Yii::$app->params["BUILD_DIR"];
            $path_file = Yii::$app->params["BACKEND_WEB"];
            $file2 = $path_file . 'files/mobgenners/' . $model->image;

            $path = Yii::$app->params["BACKEND_WEB"];
            $file = $path . $model->image;

            if ((!empty($model->image)) && (file_exists($file2))) {
                $size = Utils::formatSizeUnits(filesize($file2));
                $image = '/files/mobgenners/' . $model->image;
            }
        }

        //echo '<pre>'; print_r($model); echo '</pre>';die;
        $time = strtotime(date('Y-m-d H:i:s'));

        echo FileInput::widget([
            'name' => 'mobgennerFile[]',
            'options'=>[
                'multiple'=>false,
            ],
            'pluginOptions' => [
                'showRemove' => false,
                'uploadUrl' => Url::to(['/client/fileupload']),
                'uploadExtraData' => [
                    'mobgennerId' => $model->id,
                    //'otaProjectId' => $model->buiProIdFK,
                    'timestamp' => $time,
                ],
                'maxFileCount' => 1,
                'initialPreviewAsData'=>true,
                'initialCaption'=> (!empty($title)) ? $title : Yii::t('app', 'Select a File'),
                'initialPreview'=>[
                    $image,
                ],
                'initialPreviewConfig' => [
                    [
                        'caption' => $title,
                        'size' => $size,
                        'url' => Url::to(['/client/fileremove']),
                        'key' => $model->id
                    ],
                ],
                'overwriteInitial'=>true,
                //'maxFileSize'=>2800
            ],
            'pluginEvents' => [
                'filebatchuploadcomplete' => "function(event, files, extra) {
                    console.log('File batch upload complete');
                    $('.uploadfirst').hide();
                    $('.btn.btn-success').removeAttr('disabled');  //Enable createbutton
                }",
            ]
        ]);

        ?>

        <?= $form->field($model, 'time')->hiddenInput(['value'=> $time])->label(false); ?>
        <?php //= $form->field($model, 'buiSafename')->hiddenInput(['value'=> $model->isNewRecord ? $time : $model->buiSafename])->label(false); ?>
    </div>
    <br/>
    <div class="col-xs-6">
        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-6">
        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-3">
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-2">
        <?php

        if ($userIdRole == 1) $disabled = false;
        else $disabled = true;

        echo $form->field($model, 'active')->widget(SwitchInput::classname(), ['disabled' => $disabled, 'pluginOptions' => [
            'handleWidth' => 40,
            'onText' => 'Yes',
            'offText' => 'No',
        ]]);
        ?>
    </div>
    <div class="col-xs-3">
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-xs-6">
        <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-xs-6">
        <?= $form->field($model, 'job_title')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-xs-6">
        <?php
        echo $form->field($model, 'id_project')->dropDownList(ArrayHelper::map(Project::find()->all(), 'id', 'name'), array(
            'empty'=>'select Type',
        ));

        ?>
    </div>
    <div class="clear"></div>
</div>

<?php
$role = User::getUserIdRole();
if (($role == 1) || ($role == 12)) {
?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo Yii::t('app', 'User Information');?></h3>
        </div>
        <div class="col-xs-3">
            <?= $form->field($user, 'status')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
                'handleWidth'=>60,
                'onText'=>'Yes',
                'offText'=>'No'
            ]]);
            ?>
        </div>

        <?php if ($userNotExist) { ?>
            <div class="col-xs-3">
                <?php echo $form->field($user, 'sendEmail')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
                    'handleWidth'=>60,
                    'onText'=>'Yes',
                    'offText'=>'No'
                ]]);
                ?>
            </div>
        <?php } ?>

        <div class="col-xs-5">
            <?php
            $items[99] = 'CLIENT';

            $selected = $user->role_id ? $user->role_id : 10;
            echo $form->field($user, 'role_id')->dropDownList($items, array(
                'options' => array(
                    $selected => array('selected'=>'selected')
                )
            ));
            ?>
        </div>
        <div class="col-xs-4">
            <?php //= $form->field($model, 'user')->textInput(['value' => (isset($user->role_id)) ? $user->username : '', 'readonly' => true,  'disabled' => true]) ?>
            <?= $form->field($user, 'user')->textInput(['value' => (isset($user->role_id)) ? $user->username : '', 'readonly' => true,  'disabled' => true]) ?>
            <?php
            $user_error = $user->getErrors();
            if (isset($user_error['username'])) {
                echo '<div class="help-block">' . $user_error['username'][0] . '</div>';
            }
            ?>

        </div>

        <?php if ($userNotExist) { ?>
            <?php
            $random_password = Utils::randomPassword();
            $user->password = $random_password;
            ?>
            <div class="col-xs-6">
                <?= $form->field($user, 'password')->passwordInput(); ?>
            </div>
            <div class="col-xs-2">
                <label class="control-label" for="ramdom-password_hash">Random Password</label>
                <p><?= $random_password; ?></p>
            </div>
            <div class="col-xs-4">
                <label></label>
                <p><small><?=Yii::t('app', 'If "User Status" is active, the user would received a email with the password.'); ?></small></p>
            </div>
        <?php } ?>
        <div class="clear"></div>

    </div>
<?php } ?>

<div class="btn-footer">
    <?= $this->render('/utils/_buttonsforms', [
        'model' => $model,
    ]); ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = "
    $('#mobgenners-email').change(function(){
        console.log($('#mobgenners-email').val());
        var emailAddress = $('#mobgenners-email').val();
        $('#mobgenners-email').val(emailAddress);
        //$('#signupform-email').val(emailAddress);
        $('#mobgenners-user').val(emailAddress.substring(0, emailAddress.indexOf(\"@\")));
        console.log(emailAddress.substring(0, emailAddress.indexOf(\"@\")));
    });

";
$this->registerJs($script);
?>
