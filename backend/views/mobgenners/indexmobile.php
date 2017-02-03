<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Utils;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\MobgennersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mobgenners');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobgenners-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'List'); ?></h3>
        </div>

        <div class="box-body">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item '],
                'itemView' => '_list_user',
                'pager' => [
                    'class' => \kop\y2sp\ScrollPager::className(),
                    'negativeMargin' => '200',
                    'triggerText' => Yii::t('app', 'Load More Builds'),
                    'triggerOffset' => 3,
                    'noneLeftText' => '',
                ],
            ]) ?>
        </div>
    </div>
</div>