<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Client */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-view">

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
            //'id_project',
            [
                'attribute' => Yii::t('app', 'Project'),
                'value' => $model->project->name
            ],
            'first_name',
            'last_name',
            'email:email',
            'phone',
            'company',
            'job_title',
            'image',
            [
                'attribute' => 'active',
                'value' => $model->active == 1 ? 'Yes' : 'No'
            ],
            [
                'attribute' => 'user',
                'value' => $model->user == 1 ? 'Yes' : 'No'
            ],
            'created_at:date',
            'updated_at:date',
            //'deleted',
        ],
    ]) ?>

</div>
