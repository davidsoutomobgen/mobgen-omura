<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OtaProjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ota Projects');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ota-projects-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'List'); ?></h3>
        </div>

        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterSelector' => '#' . Html::getInputId($searchModel, 'pagesize'),
                'rowOptions'   => function ($data, $key, $index, $grid) {
                        return ['data-id' => $data->id];
                    },
                'columns' => [
                    [
                        'attribute'=>'name',
                        'format' => 'raw',
                        'value'=>function ($data) {
                            //return Html::a('/project/'.$data->proHash.'/'.$data->safename);
                            return Html::a($data->name, ['/otaprojects/'.$data->id]);

                        },
                    ],
                    [
                        'attribute'=>'proHash',
                        'label'=>'Public URL',
                        'format' => 'raw',
                        'value'=>function ($data) {
                            $frontend = Yii::$app->params['FRONTEND'];
                            return ("<a href='$frontend/project/$data->proHash/$data->safename' target='_blank' title='$data->name' alt='$data->name' >$data->proHash</a>");
                        },
                    ],
                    //'proAPIKey',
                    //'updated_at:date',
                    //['class' => 'yii\grid\ActionColumn'],
                ],
                'pager' => [
                    'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
                    'maxButtonCount'=>3,    // Set maximum number of page buttons that can be displayed
                ],
            ]);
            ?>
            <div class="clear"></div>
            <?= $this->render('/utils/_pagination', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]); ?>
        </div>
    </div>
</div>



