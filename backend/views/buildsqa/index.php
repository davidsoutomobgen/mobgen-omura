<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

use backend\models\Utils;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BuildsQaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Builds Qas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-qa-index">

    <?php
        Modal::begin([
                'header'=>'<h4>QA</h4>',
                'id' => 'modal',
                'size'=>'modal-lg',
            ]);
     
        echo "<div id='modalContent'></div>";
     
        Modal::end();
    ?>

    <?php Pjax::begin(['id'=>'buildsqaGrid']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->status == '2')
            {
                return ['class'=>'danger'];
            }if($model->status == '3')
            {
                return ['class'=>'success'];
            }
        },
        'columns' => [
             [
                'attribute' => 'status',
                'content'=>function($data){
                    return (Utils::getQAStatusById($data->status))
                    ;
                }
            ],
            'description',
            [
                'label' => Yii::t('app', 'Created by'),
                'attribute' => 'createdBy',
                'content'=>function($data){
                    return ($data->createdBy->first_name.' '.$data->createdBy->last_name);
                }
            ],
            'updated_at:date',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>



<!--
<?php

//use yii\helpers\Html;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BuildsQaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Builds Qas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-qa-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Builds Qa'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'buiId',
            'status',
            'description',
            'created_by',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
-->