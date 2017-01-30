<?php
/**
 * Created by PhpStorm.
 * User: davidsouto
 * Date: 17/11/15
 * Time: 10:28
 */

use yii\helpers\Html;
use yii\grid\GridView;
//use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PermissionsUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Permissions Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permissions-users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Permissions Users'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    //Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Permission',
                'value' => 'permissionLabel'
            ],
            'state',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{permission}',
                'buttons' => [
                    'permission' => function ($url, $model) {
                        if ($model->state == 1) {
                            return Html::a('<i class="fa fa-check-square-o"></i>', $url, [
                                'title' => Yii::t('app', 'Setup Permissions'),
                                'data-pjax'=>'w0',
                            ]);
                        }
                        else{
                            return Html::a('<i class="fa fa-square-o"></i>', $url, [
                                'title' => Yii::t('app', 'Setup Permissions'),
                                //'data-pjax'=>'w0',
                            ]);
                        }
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'permission') {
                        $url ='/permissionsusers/active/'.$model->id;
                        return $url;
                    }
                }
            ],
        ],
    ]);
    //Pjax::end();
    ?>

</div>
