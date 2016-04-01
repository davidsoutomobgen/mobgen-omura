<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OtaProjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ota Projects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ota-projects-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Ota Projects'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute'=>'name',
                'format' => 'raw',
                'value'=>function ($data) {
                    //return Html::a('/project/'.$data->proHash.'/'.$data->safename);
                    return Html::a($data->name, ['/otaprojects/'.$data->id]);

                },
            ],
            //'safename',
            //'proCreated',
            //'proModified',
            // 'id_project',
            // 'id_ota_template',
            //'proHash',
            [
                'attribute'=>'proHash',
                'label'=>'Public URL',
                'format' => 'raw',
                'value'=>function ($data) {
                    //return Html::a('/project/'.$data->proHash.'/'.$data->safename);
                    return Html::a($data->proHash, ['/project/'.$data->proHash.'/'.$data->safename]);

                },
            ],
            'proAPIKey',
            //'proAPIBuildKey',
            // 'proBuildTypes',
            // 'default_notify_email:email',
            // 'proDevUrl1:url',
            // 'proDevUrl2:url',
            // 'proDevUrl3:url',
            // 'proDevUrl4:url',
            // 'proAltUrl1:url',
            // 'proAltUrl2:url',
            // 'proAltUrl3:url',
            // 'proAltUrl4:url',
            // 'proProdUrl1:url',
            // 'proProdUrl2:url',
            // 'proProdUrl3:url',
            // 'proProdUrl4:url',
            //'created_at:date',
            'updated_at:date',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
