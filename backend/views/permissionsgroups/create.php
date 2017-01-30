<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PermissionsGroups */

$this->title = Yii::t('app', 'Create Permissions Groups');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Permissions Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permissions-groups-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
