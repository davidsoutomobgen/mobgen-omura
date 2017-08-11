<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Type'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Logo',
                'format' => 'html',
                'value' => function($data) {
                    $logo = (!empty($data->logo))?'/'.$data->logo:'/files/empty.png';

                    return Html::img(Yii::getAlias('@web').$logo, ['width'=>'48']);
                },
            ],
            //'id',
            'name',
            'description',
            //'logo',
            /*
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d/m/Y']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d/m/Y']
            ],
            */
            // 'deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
