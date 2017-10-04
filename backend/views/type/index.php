<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Types');
$this->params['breadcrumbs'][] = $this->title;
$userIdRole = User::getUserIdRole();
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="btn-header">
        <?php
        if ($userIdRole != Yii::$app->params['QA_ROLE'] && $userIdRole != Yii::$app->params['CLIENT_ROLE']) {
            echo $this->render('/utils/_buttonscreate', [
                'titulo' => Yii::t('app', 'Create Type'),
            ]);
        }
        ?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'List'); ?></h3>
        </div>

        <div class="box-body">
            <?php echo GridView::widget([
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
    </div>
</div>
