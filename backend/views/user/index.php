<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$url = 'test';
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'first_name',
            'last_name',
            'username',
            //'auth_key',
            // 'password_hash',
            // 'password_reset_token',
             'email:email',
            // 'role_id',
             'status',
            // 'created_at',
            // 'updated_at',

            /*'template' => '{link}',
            'buttons' => [
                'link' => function ($url,$model) {
                    return Html::a(
                        '<i class="fa fa-shield"></i>',
                        $url);
                },
                'link' => function ($url,$model,$key) {
                    return Html::a('Action', $url);
                },
            ],
            */
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{permission}{delete}',
                'buttons' => [
                    'permission' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-lock"></span>', $url, [
                            'title' => Yii::t('app', 'Setup Permissions'),
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'permission') {
                        $url ='permissionsusers/setup/'.$model->id;
                        return $url;
                    }
                }
            ],


        ],
    ]); ?>

</div>
