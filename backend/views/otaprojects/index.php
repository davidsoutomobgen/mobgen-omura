<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OtaProjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ota Projects');
$this->params['breadcrumbs'][] = $this->title;
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
        <?= $this->render('/utils/_buttonscreate', [
            'titulo' => Yii::t('app', 'Ota Projects'),
        ]); ?>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3  class="box-title"><?php echo Yii::t('app', 'List'); ?></h3>
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
                    // 'proDevUrl1:url',
                    // 'proDevUrl2:url',
                    // 'proDevUrl3:url',
                    // 'proDevUrl4:url',
                    // 'proAltUrl1:url',
                    // 'proAltUrl2:url',
                    // 'proAltUrl3:url',
                    // 'proAltUrl4:url',
                    // 'proProdUrl1:url',
                    // 'proProdUrl2:url',
                    // 'proProdUrl3:url',
                    // 'proProdUrl4:url',
                    //'created_at:date',
                    'updated_at:date',
                    ['class' => 'yii\grid\ActionColumn'],
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
    <div class="btn-header">
        <?= $this->render('/utils/_buttonscreate', [
            'titulo' => Yii::t('app', 'Modalidad'),
        ]); ?>
    </div>
</div>
