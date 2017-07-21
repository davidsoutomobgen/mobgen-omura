<?php
use yii\helpers\Html;

use backend\models\Client;
use backend\models\OtaProjects;
?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('app', 'Projects');?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <ul class="products-list product-list-in-box">
            <?php
            $id_project = Client::find()->getOtaProjectsClientByUser(Yii::$app->user->identity->id);
            $otaprojects = OtaProjects::find()->getOtaProjectsByProject($id_project);

            if (!$otaprojects) {
                echo '<li>You haven\'t projects.</li>';
            } else {
                foreach ($otaprojects as $project) {
                    ?>
                    <li class="item">
                        <?php
                        $link = Html::a($project->name,
                            Yii::$app->params["BACKEND"].'/otaprojects/'.$project->id,
                            ['target'=>'_blank', 'title'=>$project->name, 'alt'=>$project->name]
                        );
                        echo $link;
                        ?>
                        <span class="product-description">
                            <?php echo date('d-m-Y', $project->updated_at); ?>
                        </span>

                    </li>
                <?php }
            }
            ?>
        </ul>
    </div>
    <!--<div class="box-footer text-center">
        <a href="/otaprojects" class="uppercase"><?= Yii::t('app', 'See All OtaProjects') ?></a>
    </div>-->
</div>
