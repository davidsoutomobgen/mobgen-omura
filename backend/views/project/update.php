<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Project */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Project',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="project-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsClient' => $modelsClient,
        'new_field' => $new_field,
        'types' => $types,
        'project_types' => $project_types,
        'ota_projects' => $ota_projects,
        'value_otaprojects' => $value_otaprojects,
    ]) ?>

</div>
