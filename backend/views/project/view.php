<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Project */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'attribute' => 'logo',
                'value'=> (!empty($model->logo)) ? '/'.$model->logo : '/files/empty.png',
                'format' => ['image',['width'=>'100','height'=>'auto']],
            ],
            'name',
            'alias',
            //'logo',
            'description:ntext',
            [
                'attribute' => 'active',
                'value' => $model->active == 1 ? 'Yes' : 'No'
            ],
            [
                'attribute' => 'internal',
                'value' => $model->internal == 1 ? 'Yes' : 'No'
            ],
            'additional_information:html',
            'onboarding_document:html',
            'created_at:date',
            'updated_at:date',
            //'deleted',
        ],
    ]) ?>

    <?php
    /*
    echo $this->render('/newfieldvalues/_viewnewfields', [
        'model' => $model,
        'new_field'=>$new_field,
    ]);
    */
    ?>

</div>
