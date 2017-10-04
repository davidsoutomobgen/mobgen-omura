<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjects */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Ota Projects',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ota Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="ota-projects-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'select_buildtype' => $select_buildtype,
        'value' => $value,
        'ota_buildtypes' => $ota_buildtypes,
    ]) ?>

</div>
