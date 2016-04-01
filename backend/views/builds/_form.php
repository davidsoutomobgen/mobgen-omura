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

<div class="builds-form">
    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

    <?= $form->field($model, 'buiName')->textInput(['maxlength' => true]) ?>

    <?php
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
            echo '<label for="buiFile">File (<a href="/builds/download/'.$model->buiId.'">download</a>)</label>';
        }
        else {
           // echo 'else';
            echo $form->field($model, 'buiFile')->hiddenInput(['value' => ''])->label(true);
        }
        /*
        echo $file2;
        if (file_exists($file2)) {
            echo 'aki';
            Yii::$app->response->sendFile($file2);
        }
        else {
            echo 'else';
            die;
        }
        */
    }
    ?>


    <?php

    //echo '<pre>'; print_r($model); echo '</pre>';die;
    /*
    // Usage without a model
    echo '<label class="control-label">Upload Document</label>';
    echo FileInput::widget([
        'name' => 'attachment_3',
    ]);
    */
    //echo '<pre>'; print_r($model->buiProIdFK0->id);echo '</pre>';die;
    //echo '<pre>'; print_r($model->attributes);echo '</pre>';die;

    $time = strtotime(date('Y-m-d H:i:s'));

    echo FileInput::widget([
        'name' => 'buiFile[]',
        'options'=>[
            'multiple'=>false
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['/builds/fileupload']),
            'uploadExtraData' => [
                'buiId' => $model->buiId,
                'otaProjectId' => $model->buiProIdFK,
                'timestamp' => $time,
            ],
            'maxFileCount' => 1
        ]
    ]);
    ?>

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

    <?= $form->field($model, 'time')->hiddenInput(['value'=> $time])->label(false); ?>

    <?= $form->field($model, 'buiSafename')->hiddenInput(['value'=> $model->isNewRecord ? $time : $model->buiSafename])->label(false); ?>

    <?php //= $form->field($model, 'buiProIdFK')->hiddenInput(['value'=> $model->buiProIdFK])->label(false); ?>

    <?= $form->field($model, 'buiVersion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buiBuildNum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buiChangeLog')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'buiCerIdFK')->textInput() ?>

    <?php //= $form->field($model, 'buiType')->textInput() ?>
    <?php /*= $form->field($model, 'buiType')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
        'handleWidth'=>60,
        'value' => 'iOS',
        'onText'=>'iOS',
        'offText'=>'Android'
    ]]);
    */
    ?>

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

    <?= $form->field($model, 'buiApple')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buiSVN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buiFeedUrl')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'buiVisibleClient')->textInput() ?>
    <?= $form->field($model, 'buiVisibleClient')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
        'handleWidth'=>60,
        'onText'=>'Yes',
        'offText'=>'No'
    ]]);
    ?>

    <!--
    <?= $form->field($model, 'buiDeviceOS')->textInput() ?>

    <?= $form->field($model, 'buiLimitedUDID')->textInput() ?>

    <?= $form->field($model, 'buiBundleIdentifier')->textInput(['maxlength' => true]) ?>
    -->

    <?php //= $form->field($model, 'buiFav')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'buiFav')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
        'handleWidth'=>60,
        'onText'=>'Yes',
        'offText'=>'No'
    ]]);
    ?>
    <?php $send_email = ''; ?>
    <?= $form->field($model, 'buiSendEmail')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
        'handleWidth'=>60,
        'value' => 'Yes',
        'onText'=>'Yes',
        'offText'=>'No'
    ]]);
    ?>

    <?= $form->field($modelNotification, 'email')->textInput() ?>

    <?php if (!$model->isNewRecord) { ?>
        <?php
        echo '<div class="reload">'.Html::a('<span><i class="fa fa-share fa-2x"></i></span>','/builds/notification/'.$model->buiId, [
            'title' => Yii::t('yii', 'Send notification?'),
            'onclick'=>"var email = $('#buildsnotification-email').val();
                 $.ajax({
                    type     :'POST',
                    cache    : false,
                    data : {email: email},
                    url  : '/builds/notification/$model->buiId',
                    success  : function(response) {
                        $('#listNotifications').html(response);
                    }
             });return false;",
        ]).'</div>';
        ?>
        <?php
        /*
        echo $form->field($modelNotification, 'email', [
            'template' => "{label}\n<i class='fa fa-user'></i>\n{input}\n{hint}\n{error}"
        ])->textInput(array('placeholder' => 'Username'));
        */
        ?>
        <div class="clear"></div>

        <h3>Resume of notifications</h3>
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
    <?php }  ?>
    <div class="clear"></div>

    <div id="submit_form" class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<< JS

var unsaved = false;

$("#Builds").submit(function(e) {
    //console.log('eeee');
    unsaved = false;
});


$(":input").change(function(){ //trigers change in all input fields including text type
    unsaved = true;
});

$('.file-caption-name').bind("DOMSubtreeModified",function(){
      unsaved = true;
      $('#builds-buifile').val($('.file-caption-name').attr('title'));
});

function unloadPage(){
    if(unsaved){
        return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
    }
}
window.onbeforeunload = unloadPage;


$('form#{$model->formName()}').on('beforeSubmit', function(e)
{
            //alert( "Handler for .click() called."+ $('.file-caption-name').attr('title'));
            /*
            <div class="form-group field-builds-buifile required has-error">
            <label for="builds-buifile" class="control-label">File</label>
            <input type="hidden" value="" name="Builds[buiFile]" class="form-control" id="builds-buifile">
            <div class="help-block">File cannot be blank.</div>
            </div>
            * */
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

JS;
$this->registerJs($script);
?>

