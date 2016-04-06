<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserOptions */

$this->title = Yii::t('app', 'Create User Options');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-options-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
