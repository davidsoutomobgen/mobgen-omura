<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;


/* @var $this yii\web\View */
/* @var $model backend\models\Mobgenners */

$this->title = $model->first_name.' '.$model->last_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mobgenners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobgenners-view">
    <div class="title-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="btn-header">
        <?= $this->render('/utils/_buttonsviewnodelete', [
            'id' => $model->id,
        ]); ?>
    </div>
    <?php
    if (Yii::$app->getSession()->hasFlash('success')) {
        echo '<div class="alert alert-success">'.Yii::$app->getSession()->getFlash('success').'</div>';
    }
    if (Yii::$app->getSession()->hasFlash('error')) {
        echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
    }

    ?>
    <div class="box box-primary">
        <?= DetailView::widget([
            'model' => $model,
            //'options' => ['class' => 'col-xs-6'],
            'attributes' => [
                //'id',
                'first_name',
                'last_name',
                [
                    'attribute' => 'gender',
                    'value' => $model->gender == 'M' ? 'Male' : 'Female'
                ],
                'email:email',
                'phone',
                'skype',
                'job_title',
                [
                    'attribute'=>'image',
                    'value'=> (!empty($model->image)) ? '/files/mobgenners/'.$model->image : User::getImageUser($model->user),
                    'format' => ['image',['width'=>'100','height'=>'100']],
                ],
                'created_at:date',
                'updated_at:date',
                //'deleted',
            ],
        ]) ?>
    </div>
    <div class="title-header">
        <h1><?= Yii::t('app', 'User information') ?></h1>
    </div>
    <div class="box box-success">
        <?= DetailView::widget([
            'model' => $model,
            //'options' => ['class' => 'col-xs-6'],
            'attributes' => [
                [
                    'attribute' => 'active',
                    'value' => $model->active == 1 ? 'Yes' : 'No'
                ],
                [
                    'attribute' => 'user',
                    'value' => $model->user0->username
                ],
            ],
        ]) ?>
    </div>
    <div class="btn-footer">
        <?= $this->render('/utils/_buttonsviewnodelete', [
            'id' => $model->id,
        ]); ?>
    </div>
</div>
