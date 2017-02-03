<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjects */

$this->title = Yii::t('app', 'Create Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ota Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ota-projects-create">
    <div class="title-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'select_buildtype' => $select_buildtype,
        'value' => $value,
        'ota_buildtypes' => $ota_buildtypes,
    ]) ?>

</div>