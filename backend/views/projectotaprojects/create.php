<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OtaProjectsBuildtypes */

$this->title = Yii::t('app', 'Create Ota Projects Buildtypes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ota Projects Buildtypes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ota-projects-buildtypes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
