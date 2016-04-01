<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserGroups */

$this->title = Yii::t('app', 'Create User Groups');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-groups-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
