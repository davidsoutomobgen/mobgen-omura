<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Utils;
//use common\models\User;
/* @var $this yii\web\View */
/* @var $model backend\models\Builds */

$this->title = $model->buiName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Builds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--
    <p>
        <?= (User::getUserIdRole() == 10) ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->buiId], ['class' => 'btn btn-primary']) : '' ?>
        <?= (User::getUserIdRole() == 10) ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->buiId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) : '' ?>
  	<?= Html::a( Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-warning']);?>
    </p>
    -->
    <div class="btn-header">
        <?= $this->render('/utils/_buttonsupdate', [
            'id' => $model->buiId,
            'titulo' => Yii::t('app', 'Ota Project'),
        ]); ?>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'Listado'); ?></h3>
        </div>
	
        <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'buiId',
                'buiName',
                'buiSafename',
                //'buiCreated',
                //'buiModified',
                [
                    'attribute' => 'buiTemplate',
                    'value' => Utils::getTemplateById($model->buiId)
                ],
                'buiFile',
                'buiVersion',
                'buiBuildNum',
                'buiChangeLog:ntext',
                'buiProIdFK',
                'buiCerIdFK',
                [
                    'attribute' => 'buiType',
                    'format' => 'html',
                    'value' => $model->buiType == 1 ? Html::img(Yii::getAlias('@web').'/images/android.png', ['width'=>'32']) : Html::img(Yii::getAlias('@web').'/images/apple.png', ['width'=>'32'])
                ],
                'buiBuildType',
                'buiApple',
                'buiSVN',
                'buiFeedUrl:url',
                [
                    'attribute' => 'buiVisibleClient',
                    'value' => $model->buiVisibleClient == 1 ? 'Yes' : 'No'
                ],
                'buiDeviceOS',
                'buiLimitedUDID',
                'buiBundleIdentifier',
                'buiHash',
                [
                    'attribute' => 'buiFav',
                    'format' => 'html',
                    'value' => $model->buiFav == 1 ? '<i class="fa fa-star fa-x ' . $_SESSION['skin-color'] . '" ></i>' : '<i class="fa fa-star-o fa-x ' . $_SESSION['skin-color'] . '"></i>',
                   //'value' => $model->buiFav == 1 ? '<i class="fa fa-star fa-x" class="'.$_SESSION['skin-color'].'"  ></i>' : '<i class="fa fa-star-o fa-x" style="color:#3c8dbc"></i>'
                ],
                [
                    'label' => Yii::t('app', 'Created by'),
                    'attribute' => 'createdBy',
                    'value'=> $model->createdBy->first_name.' '.$model->createdBy->last_name
                ],
                'created_at:date',
                'updated_at:date',
            ],
        ]) ?>

        </div>
    </div>
    <div class="btn-footer">
        <?= $this->render('/utils/_buttonsupdate', [
            'id' => $model->buiId,
            'titulo' => Yii::t('app', 'Ota Project'),
        ]); ?>
    </div>
</div>


