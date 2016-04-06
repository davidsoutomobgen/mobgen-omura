<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Builds */

$this->title = Yii::t('app', 'Create Builds');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Builds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'ota_buildtypes' => $ota_buildtypes,
        'selected_buildtypes' => $selected_buildtypes,
        'templates' => $templates,
        'modelNotification' => $modelNotification,
    ]) ?>

</div>
