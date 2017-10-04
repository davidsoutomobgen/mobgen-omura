<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PermissionsUsers */

$this->title = Yii::t('app', 'Create Permissions Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Permissions Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permissions-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
