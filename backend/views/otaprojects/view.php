<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\models\BuildsDownloaded;

/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjects */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ota Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ota-projects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a( Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-warning']);?>
    </p>

    <h2><?= Yii::t('app', 'API Keys') ?></h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'name',
            //'safename',
            //'id_project',
            //'id_ota_template',
            //'proHash',
            'proAPIKey',
            'proAPIBuildKey',
            //'proBuildTypes',

            //'default_notify_email:email',
            /*
            'proDevUrl1:url',
            'proDevUrl2:url',
            'proDevUrl3:url',
            'proDevUrl4:url',
            'proAltUrl1:url',
            'proAltUrl2:url',
            'proAltUrl3:url',
            'proAltUrl4:url',
            'proProdUrl1:url',
            'proProdUrl2:url',
            'proProdUrl3:url',
            'proProdUrl4:url',
            */
            //'proCreated',
            //'proModified',
            //'created_at:date',
            //'updated_at:date',
        ],
    ]) ?>

    <div class="builds_list" >
        <h2><?= Yii::t('app', 'Builds') ?></h2>

        <div class="dropdown-list left">
            <?=Html::beginForm(['builds/bulk'],'post');?>
            <?=Html::dropDownList('action1','',[''=>'Bulk actions','1'=>'Like','2'=>'Dislike', '3'=>'Delete' ],['class'=>'form-control dropdown-list',])?>
            <?=Html::hiddenInput('buildId', $model->id);?>
            <?=Html::submitButton('Apply', ['value' => '1', 'id' => 'submit1', 'name'=>'submit', 'class' => 'btn btn-warning']);?>           
        </div>
        <div class="addbuild right">
            <?= Html::a(Yii::t('app', 'Add build'), ['/builds/create/'.$model->id], ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="clear"></div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchBuilds,
            'pager' => [
                'maxButtonCount'=>3,    // Set maximum number of page buttons that can be displayed
            ],
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                ['class' => 'yii\grid\CheckboxColumn'],
                [
                    'attribute' => 'buiType',
                    'filter'=>array("0"=>"iOS","1"=>"Android"),
                    'format' => 'html',
                    'value' => function($data) {
                        $logo = (empty($data->buiType))?'/images/apple.png':'/images/android.png';

                        return Html::img(Yii::getAlias('@web').$logo, ['width'=>'32']);
                    },
                ],
                [
                    'attribute'=>'buiName',
                    'label'=>'Name',
                    'format' => 'raw',
                    'value'=>function ($data) {
                        return (Html::a($data->buiName, ['/builds/update/'.$data->buiId]).'<p class="identifier"><small>'.$data->buiBundleIdentifier.'</small></p>');

                    },
                ],
                'buiVersion',
                [
                    'attribute'=>'buiHash',
                    'label'=>'Public URL',
                    'format' => 'raw',
                    'value'=>function ($data) {
                        $frontend = Yii::$app->params['FRONTEND'];

                        $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $data->buiFile;
                        //echo $path_file . '  ----  ' . $model->buiVisibleClient . '<br>';die;
                        if (file_exists($path_file))
                            return ("<a href='$frontend/build/$data->buiHash/$data->buiSafename' target='_blank' title='$data->buiName' alt='$data->buiName' >$data->buiHash</a>");
                        else
                            return 'Not available';

                        //return ("<a href='$frontend/$data->buiHash/$data->buiSafename' target='_blank' title='$data->buiName' alt='$data->buiName' >$data->buiHash</a>");
                        //return Html::a($data->buiHash, ['@web/build/'.$data->buiHash.'/'.$data->buiSafename]);
                    },
                ],
                [
                    'label' => Yii::t('app', 'Created by'),
                    'attribute' => 'createdBy',
                    'content'=>function($data){
                        //echo '<pre>'; print_r($data->project); echo '</pre>';
                        return ($data->createdBy->first_name.' '.$data->createdBy->last_name);
                    }

                ],
                'updated_at:date',
                [
                    'attribute'=>'buiFav',
                    'filter'=>array("0"=>"No","1"=>"Yes"),
                    'label'=>'Favorite',
                    'format'=>'raw',
                    'value' => function($data){
                        if ($data->buiFav == 1) {
                            $fav = '<span><i class="fa fa-star fa-x" style="color:#3c8dbc"></i></span>';
                            $url = '/builds/dislike/'.$data->buiId;
                            $text = Yii::t('app', 'Remove to Favorites');
                        }
                        else {
                            $fav = '<span><i class="fa fa-star-o fa-x" style="color:#3c8dbc"></i></span>';
                            $url = '/builds/like/'.$data->buiId;
                            $text = Yii::t('app', 'Add to Favorites');
                        }

                        return Html::a($fav, $url, ['title' => $text, 'data-method' => 'post']);
                    }
                ],
                [
                    'attribute'=>'buiVisibleClient',
                    'filter'=>array("0"=>"No","1"=>"Yes"),
                    'label'=>'Visible',
                    'format'=>'raw',
                    'value' => function($data){
                        if ($data->buiVisibleClient == 1) {
                            $fav = '<span><i class="fa fa-eye fa-x" style="color:#3c8dbc"></i></span>';
                            $url = '/builds/hidden/'.$data->buiId;
                            $text = Yii::t('app', 'Hidden to the client');
                        }
                        else {
                            $fav = '<span><i class="fa fa-eye-slash fa-x" style="color:#3c8dbc"></i></span>';
                            $url = '/builds/show/'.$data->buiId;
                            $text = Yii::t('app', 'Visible to the client');
                        }

                        return Html::a($fav, $url, ['title' => $text, 'data-method' => 'post']);
                    }
                ],
                [
                    'label' => Yii::t('app', 'Downloads'),
                    'attribute' => 'downloads',
                    'content'=>function($data){
                        //echo '<pre>'; print_r($data->project); echo '</pre>';
                        return BuildsDownloaded::getDownloads($data->buiId);
                    }
                ],
                //['class' => 'yii\grid\ActionColumn'],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url,$model) {
                            $url = str_replace('otaprojects', 'builds', $url);
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('app', 'View'), 'data-method' => 'post']);
                        },
                        'update' => function ($url,$model) {
                            $url = str_replace('otaprojects', 'builds', $url);
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'Update'), 'data-method' => 'post']);
                        },
                        'delete' => function($url, $model) {
                            $url = str_replace('otaprojects', 'builds', $url);
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'), 'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),'data-method' => 'post']);
                        }

                    ],
                ],



            ],
        ]); ?>
        <div class="dropdown-list">
            <?=Html::beginForm(['builds/bulk'],'post');?>
            <?=Html::dropDownList('action2','',[''=>'Bulk actions','1'=>'Like','2'=>'Dislike', '3'=>'Delete' ],['class'=>'form-control dropdown-list',])?>
            <?=Html::submitButton('Apply', ['value' => '2', 'id' => 'submit2', 'name' => 'submit', 'class' => 'btn btn-warning']);?>
        </div>

        <?= Html::endForm();?>
    </div>
    <div class="clear"></div>
</div>

<?php
$this->registerJs('
    $(document).ready(function(){

        $(\'#submit1\').on(\'click\', function(e){
            
            if ($(\'[name=action1]\').val() == 0) {
                alert (\'Select the action to apply\');
                return false;
            } else if ($(\'[name=action1]\').val() == 3) { 
               if (confirm (\'Are you sure you want to delete this item?\'))
                    return true;
                else
                    return false;
            }            
        });

        $(\'#submit2\').on(\'click\', function(e){
            
            if ($(\'[name=action2]\').val() == 0) {
                alert (\'Select the action to apply\');
                return false;
            } else if ($(\'[name=action2]\').val() == 3) { 
               if (confirm (\'Are you sure you want to delete this item?\'))
                    return true;
                else
                    return false;
            }
            
        });

    });', \yii\web\View::POS_READY);
?>






