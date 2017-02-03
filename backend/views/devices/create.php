<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Devices */

$this->title = Yii::t('app', 'Create Devices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
