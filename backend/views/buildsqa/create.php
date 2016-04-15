<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\BuildsQa */

$this->title = Yii::t('app', 'Create Builds Qa');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Builds Qas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-qa-create">

    <h4><?= Yii::t('app', 'Historic Status'); ?></h4>

    <?= $this->render('index', [                    
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
    ]) ?>

    <h4><?= Yii::t('app', 'Change Status'); ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>



</div>
