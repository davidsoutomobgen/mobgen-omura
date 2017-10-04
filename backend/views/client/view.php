<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;


/* @var $this yii\web\View */
/* @var $model backend\models\Mobgenners */

$this->title = $model->first_name.' '.$model->last_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mobgenners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


if ($model->user0 == null) {
    $user = new User();
} else {
    $user = $model->user0;
}

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
    <div class="title-header">
        <h1><?= Yii::t('app', 'User information') ?></h1>
    </div>
    <div class="box box-success">
        <?= DetailView::widget([
            'model' => $user,
            //'options' => ['class' => 'col-xs-6'],
            'attributes' => [
                [
                    'attribute' => 'active',
                    'value' => $user->status == 1 ? 'Yes' : 'No'
                ],
                [
                    'attribute' => 'user',
                    'value' => $user->username
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
