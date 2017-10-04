<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Clients');
$this->params['breadcrumbs'][] = $this->title;

//echo '<pre>';print_r($dataProvider);echo '</pre>'; die;

?>
<div class="client-index">


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
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    //'id_project',
                    [
                        'label' => Yii::t('app', 'Project'),
                        'attribute' => 'projectName',
                        'content'=>function($data){
                            //echo '<pre>'; print_r($data->project); echo '</pre>';
                            return $data->project->name;
                        }

                    ],

                    //'projectName',
          /*          [

                        //'label' => Yii::t('app', 'Project'),
                        'attribute' => 'name',
                        'content'=>function($data){
                            return $data->name;
                        }

                    ],
        */
                    'first_name',
                    'last_name',
                    'email:email',
                    'phone',
                    'company',
                    // 'job_title',
                    // 'image',
                    // 'active',
                    // 'user',
                    // 'created_at',
                    // 'updated_at',
                    // 'deleted',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>

</div>
