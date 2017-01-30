<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use common\models\User;
use backend\models\Builds;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OtaProjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ota Projects');
$this->params['breadcrumbs'][] = $this->title;
$userIdRole = User::getUserIdRole();
?>

<?= $this->render('/utils/_alerts', []); ?>
<?php
if (isset($message) && ($message == 1)) {
?>
    
        <div class="alert alert-success alert-dismissible">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
          <h4><i class="icon fa fa-check"></i>Project deleted!</h4>
          <!-- <p class="alignleft">Lorem ipsum...</p> -->
        </div>
   <?php
} else if (isset($message) && ($message == 2)) {
?>
        <div class="alert alert-danger alert-dismissible">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
          <h4><i class="icon fa fa-warning"></i>Warning!</h4>
          <p class="alignleft">This project proHash builds, contact with admin to remove the project.</p>
        </div>
<?php
}
?>

<div class="ota-projects-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="btn-header">
        <?php
        if ($userIdRole != 11) {
            echo $this->render('/utils/_buttonscreate', [
                'titulo' => Yii::t('app', 'Project'),
            ]);
        }
        ?>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'List of projects'); ?></h3>
        </div>

        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterSelector' => '#' . Html::getInputId($searchModel, 'pagesize'),
                'rowOptions'   => function ($data, $key, $index, $grid) {
                        return ['data-id' => $data->id];
                    },
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    //'id',
                    [
                        'attribute'=>'name',
                        'format' => 'raw',
                        'value'=>function ($data) {
                            //return Html::a('/project/'.$data->proHash.'/'.$data->safename);
                            return Html::a($data->name, ['/otaprojects/'.$data->id]);

                        },
                    ],
                    //'safename',
                    //'proCreated',
                    //'proModified',
                    // 'id_project',
                    // 'id_ota_template',
                    //'proHash',
                    [
                        'attribute'=>'proHash',
                        'label'=>'Public URL',
                        'format' => 'raw',
                        'value'=>function ($data) {
                            $frontend = Yii::$app->params['FRONTEND'];
                            return ("<a href='$frontend/project/$data->proHash/$data->safename' target='_blank' title='$data->name' alt='$data->name' >$data->proHash</a>");
                        },
                    ],
                    'proAPIKey',
                    //'proAPIBuildKey',
                    // 'proBuildTypes',
                    // 'default_notify_email:email',
                    //'numBuilds',
                    [
                        'attribute' => 'numBuilds',
                        //'filter' => Builds::getA(),
                        'filter'=>false,
                    ],
                    [
                        'attribute' => 'numFavs',
                        'filter'=>false,
                    ],
                    [
                        'attribute'=>'updated_at',
                        'value' => function ($model, $index, $widget) {
                            $date = date('Y-m-d H:i', $model->updated_at);
                            return $date ;
                        },
                        'filter'=>false,
                    ],
                    //['class' => 'yii\grid\ActionColumn'],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete} ',
                        'visibleButtons' => [
                            'update' => function ($model, $key, $index) {
                                return  User::getUserIdRole() == 11 ? false : true;
                            },
                            'delete' => function ($model, $key, $index) {
                                return  User::getUserIdRole() == 11 ? false : true;
                            }
                        ],
                        'buttons' => [
                            'view' => function ($url,$model) {                                
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'View'), 'data-method' => 'post']);
                            },
                            'update' => function ($url,$model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => Yii::t('app', 'Update'), 'data-method' => 'post']);
                            },
                            'delete' => function($url, $model) {
                                //$url = str_replace('otaprojects', 'builds', $url);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => Yii::t('app', 'Delete'), 'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),'data-method' => 'post']);
                            },
                            /*
                            'qa' => function($url, $model) {
                                $url = str_replace('otaprojects', 'buildsqa', $url);
                                return Html::button('<i class="fa fa-circle ></i>', ['value'=>Url::to($url),'class' => 'modalButton', 'id'=>'modalButton'.$model->buiId]);
                            },
                            */
                        ],
                    ],
                ],
                'pager' => [
                    'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
                    'maxButtonCount'=>3,    // Set maximum number of page buttons that can be displayed
                ],
            ]);
            ?>
            <div class="clear"></div>
            <?= $this->render('/utils/_pagination', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]); ?>
        </div>
    </div>
</div>
<div class="btn-footer">
    <?php
    if ($userIdRole != 11) {
        echo $this->render('/utils/_buttonscreate', [
            'titulo' => Yii::t('app', 'Project'),
        ]);
    }
    ?>
</div>
