<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\System */

$this->title = Yii::t('app', 'System');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Systems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="btn-header">
        <?= $this->render('/utils/_buttonsupdate', [
            'titulo' => Yii::t('app', 'System'),
            'id' => 1,
        ]); ?>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3  class="box-title"><?= Yii::t('app', 'General Information'); ?></h3>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    'last_update',
                    'version',
                ],
            ]) ?>
        </div>
    </div>
    <div class="btn-footer">
        <?= $this->render('/utils/_buttonsupdate', [
            'titulo' => Yii::t('app', 'System'),
            'id' => 1,
        ]); ?>
    </div>
</div>
