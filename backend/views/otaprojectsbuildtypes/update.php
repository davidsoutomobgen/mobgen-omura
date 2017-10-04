<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjectsBuildtypes */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Ota Projects Buildtypes',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ota Projects Buildtypes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="ota-projects-buildtypes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
