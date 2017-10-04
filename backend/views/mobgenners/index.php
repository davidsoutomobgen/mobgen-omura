<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Utils;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MobgennersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mobgenners');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobgenners-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="btn-header">
        <?= $this->render('/utils/_buttonscreate', [
            'titulo' => Yii::t('app', 'Mobgenner'),
        ]); ?>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'List'); ?></h3>
        </div>

        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'first_name',
                    'last_name',
                    'email:email',
                    [
                        'label' => Yii::t('app', 'Usename'),
                        'value' => 'user0.username',
                    ],
                    'roleName',
                    'phone',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
    <div class="btn-footer">
        <?= $this->render('/utils/_buttonscreate', [
            'titulo' => Yii::t('app', 'Mobgenner'),
        ]); ?>
    </div>
</div>
