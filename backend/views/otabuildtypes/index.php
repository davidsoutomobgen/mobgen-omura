<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OtaBuildTypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ota Build Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ota-build-types-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="btn-header">
        <?= $this->render('/utils/_buttonscreate', [
            'titulo' => Yii::t('app', 'Build Type'),
        ]); ?>
    </div>
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
            <h3  class="box-title"><?php echo Yii::t('app', 'List'); ?></h3>
        </div>

        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'id',
                    'name',
                    'created_at:date',
                    'updated_at:date',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
    <div class="btn-footer">
        <?= $this->render('/utils/_buttonscreate', [
            'titulo' => Yii::t('app', 'Build Type'),
        ]); ?>
    </div>
</div>
