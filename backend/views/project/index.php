<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use backend\models\ClientSearch;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Projects');
$this->params['breadcrumbs'][] = $this->title;
$userIdRole = User::getUserIdRole();

?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="btn-header">
        <?php
        if ($userIdRole != Yii::$app->params['QA_ROLE'] && $userIdRole != Yii::$app->params['CLIENT_ROLE']) {
            echo $this->render('/utils/_buttonscreate', [
                'titulo' => Yii::t('app', 'Project'),
            ]);
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
                'export'=>false,
                'pjax' => true,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'class' => 'kartik\grid\ExpandRowColumn',
                        'value' => function ($model, $key, $index, $column) {
                            return GridView::ROW_COLLAPSED;
                        },
                        'detail' => function ($model, $key, $index, $column) {
                            $searchModel = new ClientSearch();
                            $searchModel->id_project = $model->id;
                            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                            return Yii::$app->controller->renderPartial('_client', [
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                            ]);
                        },
                    ],
                    [
                        'attribute' => 'Logo',
                        'format' => 'html',
                        'value' => function($data) {
                                $logo = (!empty($data->logo))?'/'.$data->logo:'/files/empty.png';

                                return Html::img(Yii::getAlias('@web').$logo, ['width'=>'48']); },
                        //'value' => $dataProvider->getLogo($data->id),
                    ],
                    //'id',
                    'name',
                    'alias',
                    //'logo',
                    'description:ntext',
                    // 'active',
                    // 'internal',
                    // 'additional_information',
                    // 'onboarding_document',
                    // 'date_created',
                    // 'date_updated',
                    // 'deleted',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
