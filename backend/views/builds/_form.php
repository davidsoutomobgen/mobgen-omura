<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use backend\models\Templates;
use backend\models\Utils;

/* @var $this yii\web\View */
/* @var $model backend\models\Builds */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>
<div class="btn-header">
    <?= $this->render('_buttonsform', [
        'model' => $model,
    ]); ?>
</div>
<div class="clear"></div>
<?php if (!$model->isNewRecord) {
    echo $this->render('_infoapi', ['model' => $model, 'otaProject' => $otaProject]);
} ?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo Yii::t('app', 'General information');?></h3>
    </div>

    <?php if ($model->isNewRecord) { ?>
        <?= $this->render('_warning'); ?>
    <?php } ?>

    <div class="col-xs-6">
        <?php
        $size = '';
        $title = '';
        $logo = '';

        if ($model->isNewRecord) {
            echo $form->field($model, 'buiFile')->hiddenInput(['value' => '']);
        }
        else {
            //$path_file = Yii::getAlias('@webroot') . Yii::$app->params["BUILD_DIR"];
            $path_file = Yii::$app->params["BUILD_DIR"];
            $file2 = $path_file . $model->buiFile;

            $path = Yii::$app->params["BUILD_DIR"];
            $file = $path . $model->buiFile;
            if (file_exists($file2)) {
                echo $form->field($model, 'buiFile')->hiddenInput(['value' => $model->buiFile])->label(false);
                //echo '<label for="buiFile">File (<a href="'.$file.'">download</a>)</label>';
                echo '<label class="control-label" for="buiFile">File (<a href="/builds/download/'.$model->buiId.'">download</a>)</label>';

                $size = filesize($file2);
                // Now plugin create format of the size.
                //$size = Utils::formatSizeUnits(filesize($file2));

                $title = (empty($model->buiType)) ? Yii::t('app', 'iOS APP') : Yii::t('app', 'Android APP');
                $logo = (empty($model->buiType)) ? '/images/apple_logo.png' : '/images/android_logo.png';

            }
            else {
                //echo 'else';die;
                echo $form->field($model, 'buiFile')->hiddenInput(['value' => ''])->label(false);
                echo '<label class="control-label"  for="buiFile">File</label>';
                echo '<p class="alignleft"><small>'. Yii::t('app', 'Error: Filed deteled from the server.') .'</small></p>';


            }
        }

        $time = strtotime(date('Y-m-d H:i:s'));

        echo FileInput::widget([
            'name' => 'buiFile[]',
            'options'=>[
                'multiple'=>false,
            ],
            'pluginOptions' => [
                'uploadUrl' => Url::to(['/builds/fileupload']),
                'uploadExtraData' => [
                    'buiId' => $model->buiId,
                    'otaProjectId' => $model->buiProIdFK,
                    'timestamp' => $time,
                ],
                'showPreview' => false,
                'maxFileCount' => 1,
                'initialPreviewAsData'=>true,
                'initialCaption'=> (!empty($title)) ? $title : Yii::t('app', 'Select a File'),
                'initialPreview'=>[
                    $logo,
                ],
                'initialPreviewConfig' => [
                    ['caption' =>$title, 'size' => $size],
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
        <?= $form->field($model, 'buiSafename')->hiddenInput(['value'=> $model->isNewRecord ? $time : $model->buiSafename])->label(false); ?>
    </div>
    <br />
    <div class="col-xs-6">
        <?= $form->field($model, 'buiName')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-6">
        <div class="form-group field-buiTemplate required">
            <label class="control-label" for="buiTemplate">Template</label>
            <?php
            echo Select2::widget([
                'name' => 'Builds[buiTemplate]',
                'value' => $model->buiTemplate,
                'data' => $templates,
                'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Select Template ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => false,
                    'tags' => false,
                ],
            ]);
            ?>
        </div>
    </div>


    <?php //= $form->field($model, 'buiProIdFK')->hiddenInput(['value'=> $model->buiProIdFK])->label(false); ?>
    <div class="col-xs-3">
        <?= $form->field($model, 'buiVersion')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-3">
        <?= $form->field($model, 'buiBuildNum')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-3">
        <?= $form->field($model, 'buiCerIdFK')->textInput() ?>
    </div>
    <div class="col-xs-3">
        <?= $form->field($model, 'buiApple')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-3">
        <?= $form->field($model, 'buiSVN')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-3">
        <div class="form-group field-buiBuildType required">
            <label class="control-label" for="buiBuildType">Build Types</label>
            <?php
            echo Select2::widget([
                'name' => 'Builds[buiBuildType]',
                'value' => $selected_buildtypes,
                'data' => $ota_buildtypes,
                'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Select Build Type ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => false,
                    'tags' => false,
                ],
            ]);
            ?>
        </div>
    </div>

    <div class="clear"></div>
    <div class="col-xs-12">
        <?= $form->field($model, 'buiChangeLog')->textarea(['rows' => 6]) ?>
    </div>

    <?php //= $form->field($model, 'buiType')->textInput() ?>
    <?php /*= $form->field($model, 'buiType')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
        'handleWidth'=>60,
        'value' => 'iOS',
        'onText'=>'iOS',
        'offText'=>'Android'
    ]]);
    */
    ?>

    <div class="col-xs-6">
        <?= $form->field($model, 'buiFeedUrl')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-xs-3">
        <?= $form->field($model, 'buiVisibleClient')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
        'handleWidth'=>60,
        'onText'=>'Yes',
        'offText'=>'No'
    ]]);
    ?>
    </div>
    <div class="col-xs-3">
        <?= $form->field($model, 'buiFav')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
            'handleWidth'=>60,
            'onText'=>'Yes',
            'offText'=>'No'
        ]]);
        ?>
    </div>
    <div class="clear"></div>
    <div class="col-xs-4">
        <?php $send_email = ''; ?>
        <?= $form->field($model, 'buiSendEmail')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
            'handleWidth'=>60,
            'value' => 'Yes',
            'onText'=>'Yes',
            'offText'=>'No'
        ]]);
        ?>
    </div>
    <div class="col-xs-8">
        <?= $form->field($modelNotification, 'email')->textInput() ?>
        <?php
        if (!$model->isNewRecord) {
            echo '<div class="reload">' . Html::a('<span><i class="fa fa-share fa-2x"></i></span>', '/builds/notification/' . $model->buiId, [
                    'title' => Yii::t('yii', 'Send notification?'),
                    'onclick' => "
                         var email = $('#buildsnotification-email').val();
                         var button = $(this);
                         if (!button.hasClass('load')) {
                             button.addClass('load');   
                             $.ajax({
                                type     :'POST',
                                cache    : false,
                                data : {email: email},
                                url  : '/builds/notification/$model->buiId',
                                success  : function(response) {
                                    console.log(button);
                                    button.removeClass('load');
                                    $('#listNotifications').html(response);
                                }
                             })
                         }
                         return false;",
                ]) . '</div>';
        }
        ?>
    </div>
    <div class="clear"></div>
    <?php if (!$model->isNewRecord) { ?>
        <div class="col-xs-12">
            <h3><?php echo Yii::t('app', 'Resume of notifications'); ?></h3>
            <div id="listNotifications">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchBuildsNotification,
                    'columns' => [
                        'email',
                        [
                            'label' => Yii::t('app', 'Sent by'),
                            'attribute' => 'createdBy',
                            'content'=>function($data){
                                return ($data->createdBy->first_name.' '.$data->createdBy->last_name);
                            }

                        ],
                        'updated_at:datetime',
                    ],
                ]); ?>
            </div>
        </div>
        <div class="clear"></div>
    <?php }  ?>

        <!--
        <?= $form->field($model, 'buiDeviceOS')->textInput() ?>
        <?= $form->field($model, 'buiLimitedUDID')->textInput() ?>
        <?= $form->field($model, 'buiBundleIdentifier')->textInput(['maxlength' => true]) ?>
        -->
    <div class="clear"></div>
    <?php
    if ($model->isNewRecord)
        echo $this->render('_warning');
    ?>
</div>
<div class="clear"></div>

<div class="btn-footer">
    <?= $this->render('_buttonsform', [
        'model' => $model,
    ]); ?>
</div>
<?php ActiveForm::end(); ?>


<?php
$script = "
var unsaved = false;

$('#Builds').submit(function(e) {
    //console.log('eeee');
    unsaved = false;
});


$(':input').change(function(){ //trigers change in all input fields including text type
    unsaved = true;
});

$('.file-caption-name').bind('DOMSubtreeModified',function(){
      unsaved = true;
      $('#builds-buifile').val($('.file-caption-name').attr('title'));
});

function unloadPage(){
    if(unsaved){
        return '". Yii::t('app', 'You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?') ."'
    }
}
window.onbeforeunload = unloadPage;


$('form#{$model->formName()}').on('beforeSubmit', function(e)
{
            //alert( 'Handler for .click() called.'+ $('.file-caption-name').attr('title'));
            //alert($('.file-caption-name').attr('title'));
            //alert ('Antes if: ' + $('#builds-buifile').val() );
            if ($('#builds-buifile').val() === '') {
                if (typeof ($('.file-caption-name').attr('title')) === 'undefined') {

                    //alert('Upload file before!');
                    $('.field-builds-buifile').addClass('has-error');
                    $('.field-builds-buifile .help-block').text( 'File cannot be blank.');
                    return false;
                }
                else {
                    //alert('Else');

                    //alert( $('.file-caption-name').attr('title'));
                    $('#builds-buifile').val($('.file-caption-name').attr('title'));
                    //alert( $('#builds-buifile').val());
                }
            }
            else {
                   //alert( $('.file-caption-name').attr('title'));
                    $('#builds-buifile').val($('.file-caption-name').attr('title'));
                    //alert( $('#builds-buifile').val());
            }
});
";
$this->registerJs($script);
?>
