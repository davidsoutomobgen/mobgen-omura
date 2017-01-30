<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Mobgenners */

$this->title = Yii::t('app', 'Update: ', [
    'modelClass' => 'Mobgenners',
]) . ' ' . $model->first_name. ' ' . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mobgenners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->first_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="mobgenners-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
    ]) ?>

</div>
