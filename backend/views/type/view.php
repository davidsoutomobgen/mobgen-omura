<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Type */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-view">

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
            /*
            [
                'attribute' => 'logo',
                'value'=> (!empty($model->logo)) ? '/'.$model->logo : '/files/empty.png',
                'format' => ['image',['width'=>'100','height'=>'auto']],
            ],
            */
            [
                'attribute'=>'Logo',
                'value'=> (!empty($model->logo)) ? '/'.$model->logo  : '/files/empty.png',
                'format' => ['image',['width'=>'40','height'=>'40']],
            ],
            'name',
            'description',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d/m/Y']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d/m/Y']
            ],
            //'deleted',
        ],
    ]) ?>

</div>
