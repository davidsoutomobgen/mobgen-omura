<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use backend\models\Type;
use backend\models\Utils;


/* @var $this yii\web\View */
/* @var $model backend\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-form">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-flask"></i> Info Project</h4>
            </div>

            <div class="panel-body">
                <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <div class="col-xs-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-xs-6">
                    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-xs-3">
                    <?php //var_dump($model->logo); die; ?>
                    <?php if (!empty($model->logo)) { ?>
                        <div class="form-group field-project-image_logo">
                            <label class="control-label" for="project-image_logo">Logo</label>
                            <?php echo Html::img('@web/'.$model->logo, ['class' => 'img-responsive logo_project', 'width' => '80px']); ?>
                            <div class="help-block"></div>
                        </div>
                    <?php } ?>
                </div>

                <div class="col-xs-3">
                    <?= $form->field($model, 'image_logo')->fileInput() ?>
                </div>

                <div class="col-xs-3">
                    <?= $form->field($model, 'active')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
                        /*
                        'size' => 'small',
                        'onColor' => 'success',
                        'offColor' => 'default',
                        */
                        'handleWidth'=>60,
                        'onText'=>'Active',
                        'offText'=>'Inactive'
                    ]]);
                    ?>
                </div>

                <div class="col-xs-3">
                    <?= $form->field($model, 'internal')->widget(SwitchInput::classname(), [ 'pluginOptions' => [
                        'handleWidth'=>60,
                        'onText'=>'Yes',
                        'offText'=>'No'
                    ]]);
                    ?>
                </div>
                <div class="clear"></div>

                <div class="col-xs-12">
                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                </div>
                <div class="col-xs-12">
                    <div class="form-group field-project-active">
                        <label class="control-label" for="project-projectType">Type of project</label>

                        <?php
                        foreach ($types as $t) {
                            $checked = '';
                            if (in_array($t->id, $project_types)) {
                                $checked = 'checked="checked"';
                            }

                            if (!empty($t->logo))
                                echo ' <input type="checkbox" name="Project[projectType][]" value="'.$t->id.'" id="check'.$t->id.'" '.$checked.' />&nbsp;&nbsp;<label for="check'.$t->id.'"><img src="/'.$t->logo.'" title="'.$t->name.'" /></label>&nbsp;&nbsp;&nbsp;&nbsp;';
                            else
                                echo ' <input type="checkbox" name="Project[projectType][]" value="'.$t->id.'" id="check'.$t->id.'" '.$checked.' />&nbsp;&nbsp;<label for="check'.$t->id.'">'.$t->name.'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                        ?>
                        </div>

                <div class="col-xs-6">
                    <?= $form->field($model, 'additional_information')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-xs-6">
                    <?= $form->field($model, 'onboarding_document')->textInput(['maxlength' => true]) ?>
                </div>

            <div class="col-xs-12">
                <?php
                if ($value_otaprojects == -1) $error_class = 'has-error';
                else $error_class = '';
                ?>

                <?php
                //echo '<pre>'; print_r($value_otaprojects); echo '</pre>';die;
                //echo '<pre>'; print_r($ota_projects); echo '</pre>'; die;
                ?>
                <div class="form-group field-proBuildTypes required <?php echo $error_class;?> ">
                    <label class="control-label" for="proBuildTypes">OTA Projects</label>
                    <div class="hint-block"><p class=""><small><?=Yii::t('app', 'You can add new build types automatically');?></small></p></div>
                    <?php
                    echo Select2::widget([
                        'name' => 'otaProjects',
                        'value' => $value_otaprojects,
                        'data' => $ota_projects,
                        'size' => Select2::SMALL,
                        'options' => ['placeholder' => Yii::t('app', 'Select Build Type ...'), 'multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'tags' => true,
                        ],
                    ]);
                    ?>
                    <?php if ($value_otaprojects == -1) { ?>
                        <div class="help-block">OtaProjects cannot be blank.</div>
                    <?php } ?>
                </div>
            </div>
            <div class="clear"></div>
            <!-- NEW FIELDS -->
            <?php
            /*
            echo $this->render('/newfieldvalues/_formnewfields', [
                'model' => $model,
                'new_field'=>$new_field,
            ]);
            */
            ?>
            <!-- END NEW FIELDS -->
            </div>
        </div>

    </div>

    <div class="row">
        <div class="panel panel-default">
             <div class="panel-heading">
                 <h4><i class="fa fa-users"></i> Clients</h4>
             </div>

             <div class="panel-body">
                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items', // required: css class selector
                    'widgetItem' => '.item', // required: css class
                    'limit' => 10, // the maximum times, an element can be cloned (default 999)
                    'min' => 1, // 0 or 1 (default 1)
                    'insertButton' => '.add-item', // css class
                    'deleteButton' => '.remove-item', // css class
                    'model' => $modelsClient[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'name',
                        'email',
                        'phone',
                        'company',
                        'job_title',
                    ],
                ]); ?>
                <div class="container-items"><!-- widgetContainer -->
                    <!-- <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button> -->
                    <?php foreach ($modelsClient as $i => $modelClient): ?>
                        <div name="<?=$i;?>" class="item panel panel-default"><!-- widgetBody -->
                            <div class="panel-heading">
                                <h3 class='panel-title pull-left'><i class='fa fa-user'></i><?=(! $modelClient->isNewRecord) ? ' '.$modelClient->first_name . ' ' . $modelClient->last_name : ' Client';?></h3>
                                  <div class="pull-right">
                                    <button type="button" class="add-item btn btn-success btn-xs"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="expand-item btn btn-warning btn-xs" <?=( $modelClient->isNewRecord) ? 'style="display:none"': ''; ?> ><i class="fa fa-chevron-right"></i></button>
                                    <button type="button" class="close-item btn btn-warning btn-xs"  <?=( $modelClient->isNewRecord) ? '' : 'style="display:none"'; ?> ><i class="fa fa-chevron-down"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body"<?=(!$modelClient->isNewRecord) ? 'style="display:none" ': '' ;?> >
                                <?php
                                // necessary for update action.
                                if (! $modelClient->isNewRecord) {
                                    echo Html::activeHiddenInput($modelClient, "[{$i}]id");
                                }
                                ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?= $form->field($modelClient, "[{$i}]first_name")->textInput(['maxlength' => 128]) ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($modelClient, "[{$i}]last_name")->textInput(['maxlength' => 128]) ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($modelClient, "[{$i}]email")->textInput(['maxlength' => 128]) ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($modelClient, "[{$i}]phone")->textInput(['maxlength' => 128]) ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($modelClient, "[{$i}]company")->textInput(['maxlength' => 128]) ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($modelClient, "[{$i}]job_title")->textInput(['maxlength' => 128]) ?>
                                    </div>
                                </div><!-- .row -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php DynamicFormWidget::end(); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $script = <<< JS
        $('.close-item').click(function(e) {
            var name = $(this).parent().parent().parent().attr("name");
            $('div[name="'+name+'"] .panel-body').hide();
            $('div[name="'+name+'"] .close-item').hide();
            $('div[name="'+name+'"] .expand-item').show();
        });

         $('.expand-item').click(function(e) {
            var name = $(this).parent().parent().parent().attr("name");
            //alert('expand' + name);
            $('div[name="'+name+'"] .panel-body').show();
            $('div[name="'+name+'"] .expand-item').hide();
            $('div[name="'+name+'"] .close-item').show();
        });

        $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
            var set = $(".dynamicform_wrapper .panel-title");
            var len = set.length;
            set.each(function(index) {
                if (index == len - 1) {
                    //alert($(this).parent().attr("class"));
                    var ind = index+1;
                    $(this).html("<i class='fa fa-user'></i> Client: " + ind);
                    $(this).parent().parent().attr('name', 'new_client_'+ind);
                    var parent_div = $(this).parent().parent().attr("name");
                    $('div[name="'+parent_div+'"] .panel-body').show();
                    $('div[name="'+parent_div+'"] .expand-item').hide();
                    $('div[name="'+parent_div+'"] .close-item').hide();
                }
            });
        });

JS;

    $this->registerJs($script);

/*
    $js = '
        $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
            alert("ola");
            $(".dynamicform_wrapper .panel-title-address").each(function(index) {
                $(this).html("Address: " + (index + 1))
            });
        });

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
alert("adjios");

    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Address: " + (index + 1))
    });
});
';

    $this->registerJs($js);
    */

    ?>


</div>
