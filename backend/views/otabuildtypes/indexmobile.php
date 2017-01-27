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

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'List'); ?></h3>
        </div>

        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'name',
                    'updated_at:date',
                ],
            ]); ?>
        </div>
    </div>
</div>
