<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\User;
use backend\models\BuildsDownloaded;
use backend\models\Utils;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\BuildsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Builds');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'maxButtonCount'=>3,    // Set maximum number of page buttons that can be displayed
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
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

                    if (User::getUserIdRole() >= 11)
                        $exit = $fav;
                    else
                        $exit = '<a href="javascript:void(0);" onclick="addFav('.$data->buiId. ');return false;" title="$text">'.$fav.'</a>';

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
                        $fav = '<span><i class="fa fa-eye fa-x ' . $_SESSION['skin-color'] . '"></i></span>';
                        $url = '/builds/hidden/'.$data->buiId;
                        $text = Yii::t('app', 'Hidden to the client');
                    }
                    else {
                        $fav = '<span><i class="fa fa-eye-slash fa-x ' . $_SESSION['skin-color'] . '"></i></span>';
                        $url = '/builds/show/'.$data->buiId;
                        $text = Yii::t('app', 'Visible to the client');
                    }


                    if (User::getUserIdRole() >= 11)
                        $exit = $fav;
                    else
                        $exit = Html::a($fav, $url, ['title' => $text, 'data-method' => 'post']);

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
                        $url = str_replace('otaprojects', 'builds', $url);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'), 'data-method' => 'post']);
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
    ]); ?>

</div>
