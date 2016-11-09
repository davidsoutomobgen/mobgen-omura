<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Mobgenners */

$this->title = Yii::t('app', 'Create Mobgenners');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mobgenners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobgenners-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
    ]) ?>

</div>
