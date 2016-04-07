<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BuildsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Builds');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //=Html::a(Yii::t('app', 'Create Builds'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'maxButtonCount'=>3,    // Set maximum number of page buttons that can be displayed
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],
            'buiId',
            'buiName',
            'buiSafename',
            'created_at:date',
            'updated_at:date',
            // 'buiTemplate',
            // 'buiFile',
            // 'buiVersion',
            // 'buiBuildNum',
            // 'buiChangeLog:ntext',
            // 'buiProIdFK',
            // 'buiCerIdFK',
            // 'buiType',
            // 'buiBuildType',
            // 'buiApple',
            // 'buiSVN',
            // 'buiFeedUrl:url',
            // 'buiVisibleClient',
            // 'buiDeviceOS',
            // 'buiLimitedUDID',
            // 'buiBundleIdentifier',
            // 'buiHash',
            // 'buiFav',
            [
                'attribute'=>'buiFav',
                'filter'=>array("0"=>"No","1"=>"Yes"),
                'label'=>'Favorite',
                'format'=>'raw',
                'value' => function($data){
                    if ($data->buiFav == 1)
                        $fav = '<span><i class="fa fa-star fa-x" style="color:#3c8dbc"></i></span>';
                    else
                        $fav = '<span><i class="fa fa-star-o fa-x" style="color:#3c8dbc"></i></span>';

                    return $fav;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
