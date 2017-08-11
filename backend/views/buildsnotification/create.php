<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\BuildsNotification */

$this->title = Yii::t('app', 'Create Builds Notification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Builds Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-notification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
