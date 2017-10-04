<?php
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Builds */
/* @var $form yii\widgets\ActiveForm */
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchBuildsNotification,
    'columns' => [
        'email',
        [
            'label' => Yii::t('app', 'Sent by'),
            'attribute' => 'createdBy',
            'content'=>function($data){
                return ($data->createdBy->first_name.' '.$data->createdBy->last_name);
            }

        ],
        'updated_at:datetime',
    ],
]); ?>