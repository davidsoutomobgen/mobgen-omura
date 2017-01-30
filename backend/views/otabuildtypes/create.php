<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OtaBuildTypes */

$this->title = Yii::t('app', 'Create Ota Build Types');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ota Build Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ota-build-types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
