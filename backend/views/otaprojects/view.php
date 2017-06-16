<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use backend\models\BuildsDownloaded;
use backend\models\Utils;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjects */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ota Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$userIdRole = User::getUserIdRole();
?>
<?= $this->render('/utils/_alerts', []); ?>

<div class="ota-projects-view">
    <div class="title-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="btn-header">
        <?= $this->render('/utils/_buttonsview', [
            'id' => $model->id,
        ]); ?>
    </div>
    <div class="box box-primary clear">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'API Keys'); ?></h3>
        </div>

        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'proAPIKey',
                    'proAPIBuildKey',
                    'updated_at:date',
                ],
            ]) ?>
        </div>
    </div>
    <?php
    Modal::begin([
        'header'=>'<h3>QA Status</h3>',
        'id' => 'modal',
        'size'=>'modal-lg',
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
    ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'Builds'); ?></h3>
        </div>

        <div class="box-body">

        <div class="dropdown-list left">
            <?=Html::beginForm(['builds/bulk'],'post');?>
            <?=Html::dropDownList('action1','',[''=>'Bulk actions','1'=>'Like','2'=>'Dislike', '3'=>'Delete' ],['class'=>'form-control dropdown-list',])?>
            <?=Html::hiddenInput('buildId', $model->id);?>
            <?=Html::submitButton('Apply', ['value' => '1', 'id' => 'submit1', 'name'=>'submit', 'class' => 'btn btn-warning']);?>
        </div>
        <div class="addbuild right">
            <?=  ($userIdRole != 11) ? Html::a(Yii::t('app', 'Add build'), ['/builds/create/'.$model->id], ['class' => 'btn btn-primary']) : '' ?>
        </div>
        <div class="clear"></div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchBuilds,
            'filterSelector' => '#' . Html::getInputId($searchBuilds, 'pagesize'),
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

                        return Html::img(Yii::getAlias('@web').$logo, ['width'=>'24']);
                    },
                ],
                //'buiStatus',
                [
                    'attribute'=>'buiName',
                    'label'=>'Name',
                    'format' => 'raw',
                    'value'=>function ($data) {
                        return ( User::getUserIdRole() == 11 ) ?
                            (Html::a($data->buiName, ['/builds/view/'.$data->buiId]).'<p class="identifier"><small>'.$data->buiBundleIdentifier.'</small></p>')
                            :
                            (Html::a($data->buiName, ['/builds/update/'.$data->buiId]).'<p class="identifier"><small>'.$data->buiBundleIdentifier.'</small></p>')
                            ;
                    },
                ],
                'buiVersion',
                'buiBuildNum',
                [
                    'attribute'=>'buiHash',
                    'label'=>'Public URL',
                    'format' => 'raw',
                    'value'=>function ($data) {
                        $frontend = Yii::$app->params['FRONTEND'];
                        $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $data->buiFile;
                        //echo $path_file.'<br />';

                        if (file_exists($path_file))
                            return Html::a($data->buiHash, Yii::$app->params["FRONTEND"].'/build/'.$data->buiHash.'/'.$data->buiSafename, ['target'=>'_blank', 'title'=>$data->buiName, 'alt'=>$data->buiName]);
                        else
                            return 'Not available';
                    },
                ],
                [
                    'label' => Yii::t('app', 'Created by'),
                    'attribute' => 'createdBy',
                    'content'=>function($data){
                        return ($data->createdBy->first_name.' '.$data->createdBy->last_name);
                    }
                ],
                //'updated_at:datetime',
                [
                    'attribute'=>'updated_at',
                    'value' => function ($model, $index, $widget) {
                        $date = date('Y-m-d H:i', $model->updated_at);
                        return $date ;
                    },
                    'filter'=>false,
                ],
                [
                    'attribute'=>'buiFav',
                    'filter'=>array("0"=>"No","1"=>"Yes"),
                    'label'=>'Favorite',
                    'format'=>'raw',
                    'value' => function($data){
                        if ($data->buiFav == 1) {
                            $fav = '<span id="buifavicon_'.$data->buiId.'"><i class="fa fa-star fa-x ' . $_SESSION['skin-color'] . '"></i></span>';
                            $text = Yii::t('app', 'Remove to Favorites');
                            $type = 0;
                        }
                        else {
                            $fav = '<span id="buifavicon_'.$data->buiId.'"><i class="fa fa-star-o fa-x ' . $_SESSION['skin-color'] . '"></i></span>';
                            $text = Yii::t('app', 'Add to Favorites');
                            $type = 1;
                        }

                        if (User::getUserIdRole() == 11)
                            $exit = $fav;
                        else
                            $exit = '<a href="javascript:void(0);" onclick="addFav('.$data->buiId. ');return false;" title="' . $text . '">'.$fav.'</a>';

                        return $exit;
                    }
                ],
                [
                    'attribute'=>'buiVisibleClient',
                    'filter'=>array("0"=>"No","1"=>"Yes"),
                    'label'=>'Visible',
                    'format'=>'raw',
                    'value' => function($data){
                        if ($data->buiVisibleClient == 1) {
                            $fav = '<span id="buivisible_'.$data->buiId.'"><i class="fa fa-eye fa-x ' . $_SESSION['skin-color'] . '"></i></span>';
                            $text = Yii::t('app', 'Visible to the client');
                            $type = 0;
                        }
                        else {
                            $fav = '<span id="buivisible_'.$data->buiId.'"><i class="fa fa-eye-slash fa-x ' . $_SESSION['skin-color'] . '"></i></span>';
                            $text = Yii::t('app', 'Hidden to the client');
                            $type = 1;
                        }


                        if (User::getUserIdRole() == 11)
                            $exit = $fav;
                        else
                            $exit = '<a href="javascript:void(0);" onclick="toggleVisible('.$data->buiId. ');return false;" title="' . $text . '">'.$fav.'</a>';

                        return $exit;
                        //return Html::a($fav, $url, ['title' => $text, 'data-method' => 'post']);
                    }
                ],
                [
                    'label' => '<i class="fa fa-download"></i>',
                    'encodeLabel' => false,
                    'attribute' => 'downloads',
                    'content'=>function($data){
                        return BuildsDownloaded::getDownloads($data->buiId);
                    }
                ],
                [
                    'attribute'=>'status',
                    'filter'=>array("0"=>"-", "1"=>"Testing", "2"=>"With Errors", "3"=>"Correct"),
                    'label'=>Yii::t('app', 'QA Status'),
                    'format'=>'raw',
                    'value' => function($data){
                        $lastqa = 0;
                        foreach ($data->lastBuildsQas as $qa) {
                            $lastqa = $qa->status;
                        }
                        switch ($lastqa) {
                            case 0:
                                $color = 'grey';
                                break;
                            case 1:
                                $color = '#f39c12';
                                break;
                            case 2:
                                $color = '#dd4b39';
                                break;
                            case 3:
                                $color = '#00a65a';
                                break;
                        }

                        $text = Utils::getQAStatusById($lastqa);
                        $icon = '<span><i class="fa fa-circle fa-x" style="color:'.$color.'"></i></span>';
                        $url = '/buildsqa/qa/'.$data->buiId;

                        //return Html::a($icon, $url, ['title' => $text, 'data-method' => 'post']);
                        return Html::button($icon, ['value'=>Url::to($url),'class' => 'modalButton', 'id'=>'modalButton'.$data->buiId, 'title' => $text]);
                    }
                ],


                        //['class' => 'yii\grid\ActionColumn'],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update} {delete} ',
                            'visibleButtons' => [
                                 'update' => function ($model, $key, $index) {
                                    return  User::getUserIdRole() == 11 ? false : true;
                                 },
                                 'delete' => function ($model, $key, $index) {
                                    return  User::getUserIdRole() == 11 ? false : true;
                                 }
                            ],
                            'buttons' => [
                                'view' => function ($url,$model) {
                                    $url = str_replace('otaprojects', 'builds', $url);
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('app', 'View'), 'data-method' => 'post']);
                                },
                                'update' => function ($url,$model) {
                                    $url = str_replace('otaprojects', 'builds', '/builds/update/'.$model->buiId);
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                                },
                                'delete' => function($url, $model) {
                                    $url = str_replace('otaprojects', 'builds', $url);
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Yii::t('app', 'Delete'), 'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),'data-method' => 'post']);
                                },
                                /*
                                'qa' => function($url, $model) {
                                    $url = str_replace('otaprojects', 'buildsqa', $url);
                                    return Html::button('<i class="fa fa-circle ></i>', ['value'=>Url::to($url),'class' => 'modalButton', 'id'=>'modalButton'.$model->buiId]);
                                },
                                */
                            ],
                        ],
            ],
            'pager' => [
                'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
                'maxButtonCount'=>3,    // Set maximum number of page buttons that can be displayed
            ],
        ]); ?>
            <div class="clear"></div>
            <div id="otaviews" class="dropdown-list">
                <?=Html::beginForm(['builds/bulk'],'post');?>
                <?=Html::dropDownList('action2','',[''=>'Bulk actions','1'=>'Like','2'=>'Dislike', '3'=>'Delete' ],['class'=>'form-control dropdown-list',])?>
                <?=Html::submitButton('Apply', ['value' => '2', 'id' => 'submit2', 'name' => 'submit', 'class' => 'btn btn-warning']);?>
            </div>
            <?= $this->render('/utils/_pagination', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchBuilds,
            ]); ?>

        <?= Html::endForm();?>
    </div>
    <div class="clear"></div>
    </div>
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

        $(\'.user-menu\').on(\'click\', function(e){
            $(\'.user-menu\').addClass(\'open\');
        });

    });', \yii\web\View::POS_READY);
?>
<script>
    function addFav(buiId){
        var parametros = {
            "buiId" : buiId
        };

        $.ajax({
            data: parametros,
            url: '../builds/like/' + buiId,
            type: 'post',
            beforeSend: function () {
                $("#buifavicon_" + buiId).html("Process...");
            },
            success: function (response) {
                $("#buifavicon_" + buiId).html(response);
            }
        });
    }
    function toggleVisible(buiId){
        var parametros = {
            "buiId" : buiId
        };

        $.ajax({
            data: parametros,
            url: '../builds/visible/' + buiId,
            type: 'post',
            beforeSend: function () {
                $("#buivisible_" + buiId).html("Process...");
            },
            success: function (response) {
                $("#buivisible_" + buiId).html(response);
            }
        });
    }
</script>
