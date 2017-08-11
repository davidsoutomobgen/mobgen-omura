<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Permissions */
/*
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
*/
?>


<div class="callout callout-danger">
    <h4>Pemission denied!</h4>

    <p>You don't have permission to do this action: <?= $model['action'].' '.$model['model'];?></p>
    <p><?= Html::a( 'Back', Yii::$app->request->referrer); ?></p>
</div>


