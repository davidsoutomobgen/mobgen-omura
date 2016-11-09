<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use backend\models\BuildsDownloaded;
use backend\models\Utils;

/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjects */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ota Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/utils/_alerts', []); ?>

<div class="ota-projects-view">
    <div class="title-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="box box-primary clear">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'API Keys'); ?></h3>
        </div>

        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'proAPIKey',
                    'proAPIBuildKey',
                ],
            ]) ?>
        </div>
    </div>

    <?php echo $this->render('_searchmobile',['model' => $searchBuilds]); ?>
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

        <div class="box-body parent">

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item '],
                /*'itemView' => function ($model, $key, $index, $widget) {
                    return Html::a(Html::encode($model->buiName), ['view', 'id' => $model->buiId]);
                },
                */
                    /*
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_list_item',['model' => $model]);
                },
                */
                'itemView' => '_list_item',
                //'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
                'pager' => [
                    'class' => \kop\y2sp\ScrollPager::className(),
                    'negativeMargin' => '200',
                    'triggerText' => Yii::t('app', 'Load More news'),
                    'triggerOffset' => 3,
                    'noneLeftText' => '',
                ],
            ]) ?>
        <?= Html::endForm();?>
    </div>
    <div class="clear"></div>
</div>
<?php
$this->registerJs("
    $('article').click(function (e) {
        console.log($(this).data('key'));        
        var full_path = '" . Yii::$app->params["FRONTEND"]  . "/build/' + $(this).data('key');
        $(location).attr('href',full_path);
    });
");
?>





