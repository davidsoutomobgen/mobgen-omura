<?php
/* @var $this yii\web\View */

$this->title = Yii::$app->params['projectName'];

use yii\widgets\Pjax;
use yii\helpers\Html;
?>


<div class="site-index">
    <div id="dashboard" class="ng-scope">
        <div class="body-content">
            <div class="dashboard ng-scope">
                <div id="dashboard-drag-area" class="dashboard-inner">

                    <?php if ((isset($_SESSION['welcome-close'])) && ($_SESSION['welcome-close'] == 0 )) { ?>
                        <div id="welcometile" class="ng-isolate-scope">
                            <div class="tile ng-hide" >
                                <div class="welcome-tile">
                                    <a id="welcome-close"  href="#" class="welcome-close white right">
                                        <i  class="fa fa-close"></i>
                                    </a>
                                    <h2 class="ng-binding">OTA Share</h2>
                                    <i  class="fa fa-home fa-5x white" style="float:left"></i>
                                    <p class="lead ng-binding">
                                        Customize your dashboard, change the color, setup the header, move and re-order the menu.
                                    </p>
                                    <!--
                                    <ul>
                                        <li><i class="fa fa-question"></i><span><a class="js-context-help-link ng-binding" href="#">Open help</a></span></li>
                                    </ul>
                                    -->
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php $userId= \Yii::$app->user->identity->id; ?>
                    <ul id="dashboard_ul" class="">
                        <!-- Otashare -->
                        <li id="projects" class="tile ng-scope pos_1">
                            <div class="tile-outer-wrapper ng-isolate-scope">
                                <div class="ng-scope">
                                    <div class="tile-background">
                                    </div>
                                    <div class="tile-mask">
                                        <div class="tile-wrapper">
                                            <div class="tile-dead">
                                                <a class="tile-btn tile-dead-btn" href="/otaprojects" target="_top" >
                                                    <div class="ng-isolate-scope">
                                                        <i class="fa fa-share-alt fa-4x"></i>
                                                    </div>
                                                    <h4 class="ng-binding">Otashare</h4>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php
                        if ($userId == 1) {
                            ?>
                            <!-- Projects -->
                            <!--
                            <li id="projects" class="tile ng-scope pos_1">
                                <div class="tile-outer-wrapper ng-isolate-scope">
                                    <div class="ng-scope">
                                        <div class="tile-background">
                                        </div>
                                        <div class="tile-mask">
                                            <div class="tile-wrapper">
                                                <div class="tile-dead">
                                                    <a class="tile-btn tile-dead-btn" href="/project" target="_top">
                                                        <div class="ng-isolate-scope">
                                                            <i class="fa fa-flask fa-4x"></i>
                                                        </div>
                                                        <h4 class="ng-binding">Projects</h4>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            -->
                            <!-- Project type -->
                            <!--
                            <li id="project_type" class="tile ng-scope pos_2">
                                <div class="tile-outer-wrapper ng-isolate-scope">
                                    <div class="ng-scope">
                                        <div class="tile-background">
                                        </div>
                                        <div class="tile-mask">
                                            <div class="tile-wrapper">
                                                <div class="tile-dead">
                                                    <a class="tile-btn tile-dead-btn" href="/type" target="_top">
                                                        <div class="ng-isolate-scope">
                                                            <i class="fa fa-desktop fa-4x"></i>
                                                        </div>
                                                        <h4 class="ng-binding">Project Type</h4>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            -->
                            <!-- Company -->
                            <!--
                            <li id="mobgenners" class="tile ng-scope pos_3">
                                <div class="tile-outer-wrapper ng-isolate-scope">
                                    <div class="ng-scope">
                                        <div class="tile-background">
                                        </div>
                                        <div class="tile-mask">
                                            <div class="tile-wrapper">
                                                <div class="tile-dead">
                                                    <a class="tile-btn tile-dead-btn" target="_top" href="/company">
                                                        <div class="ng-isolate-scope">
                                                            <i class="fa fa-institution fa-4x"></i>
                                                        </div>
                                                        <h4 class="ng-binding">Company</h4>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li-->
                            <!-- Company -->
                            <!--
                            <li id="mobgenners" class="tile ng-scope pos_3">
                                <div class="tile-outer-wrapper ng-isolate-scope">
                                    <div class="ng-scope">
                                        <div class="tile-background">
                                        </div>
                                        <div class="tile-mask">
                                            <div class="tile-wrapper">
                                                <div class="tile-dead">
                                                    <a class="tile-btn tile-dead-btn" target="_top" href="/offices">
                                                        <div class="ng-isolate-scope">
                                                            <i class="fa fa-building-o fa-4x"></i>
                                                        </div>
                                                        <h4 class="ng-binding">Company</h4>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            -->
                            <!-- Mobgenners -->
                            <!--
                            <li id="mobgenners" class="tile ng-scope pos_3">
                                <div class="tile-outer-wrapper ng-isolate-scope">
                                    <div class="ng-scope">
                                        <div class="tile-background">
                                        </div>
                                        <div class="tile-mask">
                                            <div class="tile-wrapper">
                                                <div class="tile-dead">
                                                    <a class="tile-btn tile-dead-btn" target="_top" href="/mobgenners">
                                                        <div class="ng-isolate-scope">
                                                            <i class="fa fa-circle-o fa-4x"></i>
                                                        </div>
                                                        <h4 class="ng-binding">Mobgenners</h4>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            -->
                            <!-- Devices -->
                            <li id="devices" class="tile ng-scope pos_3">
                                <div class="tile-outer-wrapper ng-isolate-scope">
                                    <div class="ng-scope">
                                        <div class="tile-background">
                                        </div>
                                        <div class="tile-mask">
                                            <div class="tile-wrapper">
                                                <div class="tile-dead">
                                                    <a class="tile-btn tile-dead-btn" target="_top" href="/devices">
                                                        <div class="ng-isolate-scope">
                                                            <i class="fa fa-tablet fa-4x"></i>
                                                        </div>
                                                        <h4 class="ng-binding">Test Devices</h4>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Administrator -->
                            <!--
                            <li id="administrator" class="tile ng-scope pos_3">
                                <div id="admin" class="tile-outer-wrapper ng-isolate-scope">
                                    <div class="ng-scope">
                                        <div class="tile-background">
                                        </div>
                                        <div class="tile-mask">
                                            <div class="tile-wrapper">
                                                <div class="tile-dead">
                                                    <a class="tile-btn tile-dead-btn" target="_top" href="/groups">
                                                        <div class="ng-isolate-scope">
                                                            <i class="fa fa-cog fa-4x"></i>
                                                        </div>
                                                        <h4 class="ng-binding">Administrator</h4>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="subadmin" style="display:none">
                                    <ul>
                                        <li id="templates" class="tile ng-scope pos_3">
                                            <div class="tile-outer-wrapper ng-isolate-scope">
                                                <div class="ng-scope">
                                                    <div class="tile-background">
                                                    </div>
                                                    <div class="tile-mask">
                                                        <div class="tile-wrapper">
                                                            <div class="tile-dead">
                                                                <a class="tile-btn tile-dead-btn" target="_top" href="/groups">
                                                                    <div class="ng-isolate-scope">
                                                                        <i class="fa fa-indent fa-2x"></i>
                                                                    </div>
                                                                    <p class="ng-binding">Templates</p>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li id="visual_options" class="tile ng-scope pos_3">
                                            <div class="tile-outer-wrapper ng-isolate-scope">
                                                <div class="ng-scope">
                                                    <div class="tile-background">
                                                    </div>
                                                    <div class="tile-mask">
                                                        <div class="tile-wrapper">
                                                            <div class="tile-dead">
                                                                <a class="tile-btn tile-dead-btn" target="_top" href="/groups">
                                                                    <div class="ng-isolate-scope">
                                                                        <i class="fa fa-cubes fa-2x"></i>
                                                                    </div>
                                                                    <p class="ng-binding">Visual Option</p>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li id="groups" class="tile ng-scope pos_3">
                                            <div class="tile-outer-wrapper ng-isolate-scope">
                                                <div class="ng-scope">
                                                    <div class="tile-background">
                                                    </div>
                                                    <div class="tile-mask">
                                                        <div class="tile-wrapper">
                                                            <div class="tile-dead">
                                                                <a class="tile-btn tile-dead-btn" target="_top" href="/groups">
                                                                    <div class="ng-isolate-scope">
                                                                        <i class="fa fa-cubes fa-2x"></i>
                                                                    </div>
                                                                    <p class="ng-binding">Groups</p>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
    -->
                            <!--
                            <li class="tile tile-shrink ng-hide">
                                <a class="tile-dead-btn tile-btn tile-btn-add" href="javascript:void(0);" title="Add new application">
                                    <i aria-hidden="true" class="icon-plus cms-icon-200 tile-icon"></i><span class="sr-only">Add new application</span>
                                </a>
                            </li>
                            -->
                        <?php
                        }
                        ?>
                    </ul>
                </div>

                <div class="btn-group dropup anchor-dropup pull-right">
                    <button id="edit_dashboard" type="button" class="btn btn-default sortable-off" title="Edit dashboard">
                        <i class="fa fa-cog fa-1x"></i><span class="sr-only">Edit dashboard</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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


$this->registerJs('
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

