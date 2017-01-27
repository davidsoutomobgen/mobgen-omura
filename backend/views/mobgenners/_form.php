<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\switchinput\SwitchInput;
use common\models\User;
use backend\models\Utils;


/* @var $this yii\web\View */
/* @var $model backend\models\Mobgenners */
/* @var $form yii\widgets\ActiveForm */

$userIdRole = User::getUserIdRole();
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


        if ($model->isNewRecord) {
            echo $form->field($model, 'image')->hiddenInput(['value' => '']);
        }
        else {
            //$path_file = Yii::getAlias('@webroot') . Yii::$app->params["BUILD_DIR"];
            $path_file = Yii::$app->params["BACKEND_WEB"];
            $file2 = $path_file . 'files/mobgenners/' . $model->image;

            $path = Yii::$app->params["BACKEND_WEB"];
            $file = $path . $model->image;

            if ((!empty($model->image)) && (file_exists($file2))) {
                echo $form->field($model, 'image')->hiddenInput(['value' => $model->image])->label(false);
                $size = Utils::formatSizeUnits(filesize($file2));
                $image = '/files/mobgenners/' . $model->image;
            }
            else {
                $image = User::getImageUser($model->user);
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
                'uploadUrl' => Url::to(['/mobgenners/fileupload']),
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
                        'url' => Url::to(['/mobgenners/fileremove']),
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
    <div class="col-xs-3">

        <?php

        if ($userIdRole == 1) $disabled = false;
        else $disabled = true;

        echo $form->field($model, 'active')->widget(SwitchInput::classname(), ['disabled' => $disabled, 'pluginOptions' => [
            'handleWidth' => 60,
            'onText' => 'Yes',
            'offText' => 'No',
        ]]);
        ?>
    </div>
    <div class="col-xs-3">
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-3">
        <?php
        $genders['M'] = 'MALE';
        $genders['F'] = 'FEMALE';

        echo $form->field($model, 'gender')->dropDownList(
            $genders
        );
        ?>
    </div>
    <div class="col-xs-6">
        <?= $form->field($model, 'skype')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-xs-6">
        <?= $form->field($model, 'job_title')->textInput(['maxlength' => true]) ?>
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
        <div class="col-xs-5">
            <?php
            $items[1] = 'ADMIN';
            $items[10] = 'DEVELOPER';
            $items[11] = 'QA';
            $items[12] = 'LEAD';

            if (isset($model->user0->role_id))
                $model->role_id = $model->user0->role_id;

            echo $form->field($user, 'role_id')->dropDownList(
                $items,
                ['prompt'=>'']
            );
            ?>
        </div>
        <div class="col-xs-4">
            <?php //= $form->field($model, 'user')->textInput(['value' => (isset($model->user0->role_id)) ? $model->user0->username : '', 'readonly' => true,  'disabled' => true]) ?>
            <?= $form->field($user, 'user')->textInput(['value' => (isset($model->user0->role_id)) ? $model->user0->username : '', 'readonly' => true,  'disabled' => true]) ?>
            <?php
            $user_error = $user->getErrors();
            if (isset($user_error['username'])) {
                echo '<div class="help-block">' . $user_error['username'][0] . '</div>';
            }
            ?>

        </div>

        <?php if ($model->isNewRecord) { ?>
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
