<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Utils;
use common\models\User;
/* @var $this yii\web\View */
/* @var $model backend\models\Builds */

$this->title = $model->buiName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ota Projects'), 'url' => ['/otaprojects']];
$this->params['breadcrumbs'][] = ['label' => $model->buiProIdFK0->name, 'url' => ['/otaprojects/'.$model->buiProIdFK]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="builds-view">
    <div class="title-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <div class="btn-header">
        <p>
            <?php if (User::getUserIdRole() < 11) { ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->buiId], ['class' => 'btn btn-primary']) ?>
            <?php } ?>
            <?= Html::a( Yii::t('app', 'Back'), '/otaprojects/'.$model->buiProIdFK, ['class' => 'btn btn-warning']);?>
        </p>
    </div>

    <div class="box box-primary clear">
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
                    'value' => function($data){
                        if ($data->buiVisibleClient == 1) {
                            $text = Yii::t('app', 'Visible to the client');
                        } elseif ($data->buiVisibleClient == 2) {
                            $text = Yii::t('app', 'Visible to registered users');
                        } else {
                            $text = Yii::t('app', 'Hidden to the client');
                        }
                        return $text;
                    }

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
    <?php if (User::getUserIdRole() < 11) { ?>
        <div class="btn-footer">
            <?= $this->render('/utils/_buttonsupdate', [
                'id' => $model->buiId,
                'titulo' => Yii::t('app', 'Build'),
            ]); ?>
        </div>
    <?php } ?>
</div>
