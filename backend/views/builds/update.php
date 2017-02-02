<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Builds */

$this->title = Yii::t('app', 'Update Build: ', [
    'modelClass' => 'Builds',
]) . ' ' . $model->buiName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Builds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->buiId, 'url' => ['view', 'id' => $model->buiId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="builds-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'ota_buildtypes' => $ota_buildtypes,
        'selected_buildtypes' => $selected_buildtypes,
        'templates' => $templates,
        'modelNotification' => $modelNotification,
        'searchBuildsNotification' => $searchBuildsNotification,
        'dataProvider' => $dataProvider,
        'otaProject' => $otaProject,
    ]) ?>

</div>
