<?php
/* @var $this yii\web\View */

$this->title = Yii::$app->params['projectName'];

use yii\widgets\Pjax;
use yii\helpers\Html;

use backend\models\Builds;
use backend\models\BuildsDownloaded;
use backend\models\OtaProjects;
use backend\models\Mobgenners;
use backend\models\System;
use backend\models\Utils;
?>
<section class="content-header">
    <h1>
        Dashboard
        <small>Version <?= System::getVersion();?></small>
    </h1>
    <!--
    <ol class="breadcrumb right">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
    -->
</section>
<section class="content">
    <!-- TABLE: LATEST BUILDS -->
    <div class="row">
        <div class="col-md-8">
            <?php
            $lastBuildsUser = Builds::find()->getLastBuildsByUser(Yii::$app->user->identity->id, 0, 5);
            if (empty($lastBuildsUser)) $n = 9;
            else $n = 6;

            $lastBuilds = Builds::find()->getLastBuilds(0, $n);

            echo $this->render('_resume_buildsmobile', [
                'bordercolor' => 'success',
                'title' => Yii::t('app', 'Latest Builds'),
                'lastbuilds' => $lastBuilds,
                'showcreated' => true,
            ]);


            //LAST PROJECTS
            echo $this->render('/otaprojects/_block', [
                'bordercolor' => 'danger',
                'header' => true,
            ]);
            
            if (!empty($lastBuildsUser)) {
                echo $this->render('_resume_buildsmobile', [
                    'bordercolor' => 'primary',
                    'title' => Yii::t('app', 'Your Latest Builds'),
                    'lastbuilds' => $lastBuildsUser,
                    'showcreated' => false,
                ]);
            }

            //PROFILE
            echo $this->render('/user/_block', [
                'bordercolor' => 'danger',
                'header' => true,
            ]);
            ?>
        </div>
    </div>
    <div class="clear"></div>
</section>

<?php

$this->registerJs('
                    $(document).ready(function(){

                         $(\'#edit_dashboard\').click(function(){
                            if ($(\'#edit_dashboard\').hasClass(\'sortable-off\')){
                                $(\'#edit_dashboard\').removeClass(\'sortable-off\');
                                $(\'#edit_dashboard\').addClass(\'sortable-on\');
                                $(\'#dashboard_ul\').addClass(\'ui-sortable\');
                                $(\'#dashboard_ul.ui-sortable\').sortable();
                            }
                            else {
                                $(\'#edit_dashboard\').removeClass(\'sortable-on\');
                                $(\'#edit_dashboard\').addClass(\'sortable-off\');
                                $(\'#dashboard_ul.ui-sortable\').sortable(\'disable\');
                                $(\'#dashboard_ul\').removeClass(\'ui-sortable\');
                            }
                         });
                    });', \yii\web\View::POS_READY);


/*$this->registerJs('
                    $(document).ready(function(){
                         $(\'#welcome-close\').click(function(){
                              var id_user = ' . \Yii::$app->user->identity->id . ';
                              var option = \'welcome-close\';
                              var value = 1;

                              $.ajax({
                                type: \'POST\',
                                url : \'/useroptions/updateajax/\',
                                data : {userid: id_user, option: option, value: value},
                                success : function() {
                                  $(\'#welcometile\').hide();
                                }
                            });
                         });

                    });', \yii\web\View::POS_READY);
*/

$this->registerJs('
                    $(document).ready(function(){
                         $(\'#administrator\').mouseenter(function(){
                                 $(\'#admin\').hide();
                                 $(\'#subadmin\').show();
                         });

                         $(\'#administrator\').mouseleave(function(){
                                 $(\'#subadmin\').hide();
                                  $(\'#admin\').show();
                         });
                    });', \yii\web\View::POS_READY);

?>

