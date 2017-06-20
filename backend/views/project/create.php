<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Project */

$this->title = Yii::t('app', 'Create Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$new_field = array();
$project_types = array();

?>
<div class="project-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'         => $model,
        'modelsClient'  => $modelsClient,
        'new_field' => $new_field,
        'types' => $types,
        'project_types' => $project_types
    ]) ?>

</div>
