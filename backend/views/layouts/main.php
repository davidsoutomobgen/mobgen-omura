<?php
use backend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\bootstrap\Nav;
//use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\models\UserOptions;

/* @var $this \yii\web\View */
/* @var $content string */

DashboardAsset::register($this);

//$session = Yii::$app->session;
//if ($session->has('user_id')) {
if (isset(Yii::$app->user->identity->id)) {
    $userid =  Yii::$app->user->identity->id;

    $options = UserOptions::find()->getUserOptionsByUserId((int) $userid);
    $session = Yii::$app->session;
    foreach ($options as $opt) {
        //var_dump($opt);
        //echo '<br>';
        if ($opt['type'] == 'integer')
            $session->set($opt['variable'], (int) $opt['value']);
        else if ($opt['type'] == 'string')
            $session->set($opt['variable'], (string) $opt['value']);
    }
}
else {
    //echo 'Session close'; die;
    return Yii::$app->response->redirect(Url::to(['/site/logout']));
}

//$variable = 'fixed-header';
//$model = UserOptions::find()->getVariable($userid, 'fixed_header');



//echo '<pre>'; print_r($this->context->id); echo '</pre>'; die;
//var_dump($_SESSION);die;
//AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="/images/favicon/mobgen_logo_32.png">

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="/images/favicon/favicon_57x57.png">

    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/favicon_72x72.png">

    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/favicon_114x114.png">

    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/favicon_144x144.png">
    <?php $this->head() ?>
    <?php 
    if (!isset($_SESSION['skin-color'])) {
        $_SESSION['skin-color'] = 'skin-blue';
    }
    ?>
</head>
<body class="
        <?= (isset($_SESSION['skin-color'])) ? $_SESSION['skin-color'] : 'skin-blue';?>
        sidebar-mini
        <?= ((isset($_SESSION['fixed-header'])) && ($_SESSION['fixed-header'] == 1 )) ? 'fixed' : '';?>
        <?= ((isset($_SESSION['layout-boxed'])) && ($_SESSION['layout-boxed'] == 1 )) ? 'layout-boxed' : '';?>
        <?= ((isset($_SESSION['sidebar-collapse'])) && ($_SESSION['sidebar-collapse'] == 1 )) ? 'sidebar-collapse' : '';?>
        <?= ((isset($_SESSION['control-sidebar-open'])) && ($_SESSION['control-sidebar-open'] == 1 )) ? 'control-sidebar-open' : '';?>
        ">
    <?php $this->beginBody() ?>
    <div class="wrap wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="index2.html" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b><?= Yii::$app->params['projectNameShort'];?></b>s</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b><?= Yii::$app->params['projectName'];?></b>Share</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="/files/user2-128x128.png" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo Yii::$app->user->identity->first_name.' '.Yii::$app->user->identity->last_name ; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="/files/user2-128x128.png" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo Yii::$app->user->identity->first_name.' '.Yii::$app->user->identity->last_name ; ?> <!-- - Backend Developer -->
                                        <!-- <small>Member since Nov. 2012</small> -->
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <!--
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Projects </a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li>
                                -->
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="/site/logout" class="btn btn-default btn-flat"  data-method="post">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="/files/user2-128x128.png" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo Yii::$app->user->identity->first_name.' '.Yii::$app->user->identity->last_name ; ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- search form -->
                <!--
                <form action="search" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
                -->
                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?php $userId= \Yii::$app->user->identity->id; ?>

                <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>
                    <?php
                    if ($userId == 1) {
                    ?>
                    <li class="<?=($this->context->id == 'site') ? 'active' : '' ?> treeview">
                        <a href="/site">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <?php } ?>

                    <li class="treeview <?=($this->context->id == 'otaprojects') ? 'active' : ' ' ?>">
                        <a href="/otaprojects">
                            <i class="fa fa-share-alt"></i> <span>OTA Share</span>
                        </a>
                    </li>
                    <li class="treeview <?=($this->context->id == 'otabuildtypes') ? 'active' : ' ' ?>">
                        <a href="#">
                            <i class="fa fa-cog"></i>
                            <span>OTA Share Admin</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?=($this->context->id == 'otabuildtypes') ? 'active' : ' ' ?>"><a href="/otabuildtypes"><i class="fa fa-ellipsis-h"></i> Build Types</a></li>
                        </ul>
                    </li>
                    <li class="treeview <?=($this->context->id == 'devices') ? 'active' : ' ' ?>">
                        <a href="/devices">
                            <i class="fa fa-tablet"></i> <span>Test Devices</span>
                        </a>
                    </li>
                    <?php
                    if ($userId == 1) {
                    ?>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-cog"></i>
                                <span>Administrator</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <!--
                                <li><a href="/groups"><i class="fa fa-cubes"></i>Groups</a></li>
                                <li><a href="/groups/users"><i class="fa fa-cubes"></i>User Groups </a></li>
                                -->
                                <li><a href="/useroptions"><i class="fa fa-paint-brush"></i> Visual Options</a></li>
                                <li><a href="/options"><i class="fa fa-plus-square-o"></i>Parameters Visual Options</a></li>
                                <li><a href="/permissions"><i class="fa fa-shield"></i>Permissions</a></li>
                                <li><a href="/user"><i class="fa fa-users"></i>Users</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
                <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">

		<?= Breadcrumbs::widget([
                	'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        	]) ?>
            </section>
            <!-- Main content -->
            <section class="content">
                <?= $content ?>
            </section>
        </div><!-- /.content-wrapper -->
        <div class="clear"></div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 0.1.2
            </div>
            <strong>Copyright &copy; 2015 <a href="http://www.mobgen.com">MOBGEN</a>.</strong> All rights reserved.
        </footer>

        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <!--
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active"><a data-toggle="tab" href="#control-sidebar-theme-demo-options-tab"><i class="fa fa-wrench"></i></a></li>
                <li><a data-toggle="tab" href="#control-sidebar-home-tab"><i class="fa fa-home"></i></a></li>
                <li><a data-toggle="tab" href="#control-sidebar-settings-tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            -->
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Layout option tab content -->
                <div id="control-sidebar-theme-demo-options-tab" class="tab-pane active">
                    <div>
                        <h4 class="control-sidebar-heading">Layout Options</h4>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                <input id="fixed-header" type="checkbox" class="pull-right" data-layout="fixed" <?=((isset($_SESSION['fixed-header'])) && ($_SESSION['fixed-header'] == 1 )) ? 'CHECKED' : '&bnsp;';?> >
                                Fixed layout
                            </label>
                            <p>Activate the fixed layout. You can't use fixed and boxed layouts together</p>
                        </div>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                <input id="layout-boxed" type="checkbox" class="pull-right" data-layout="layout-boxed"  <?=((isset($_SESSION['layout-boxed'])) && ($_SESSION['layout-boxed'] == 1 )) ? 'CHECKED' : '&bnsp;';?>>
                                Boxed Layout
                            </label>
                            <p>Activate the boxed layout</p>
                        </div>
                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                <input id="sidebar-collapse" type="checkbox" class="pull-right" data-layout="sidebar-collapse"> Toggle Sidebar</label>
                            <p>Toggle the left sidebar's state (open or collapse)</p>
                        </div>
                        <!--
                        <div class="form-group">
                            <label  class="control-sidebar-subheading"><input type="checkbox" class="pull-right" data-enable="expandOnHover" disabled="disabled"> Sidebar Expand on Hover</label>
                            <p>Let the sidebar mini expand on hover</p>
                        </div>
                        -->
                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                <input id="control-sidebar-open" type="checkbox" class="pull-right" data-controlsidebar="control-sidebar-open">
                                Toggle Right Sidebar Slide
                            </label>
                            <p>Toggle between slide over content and push content effects</p>
                        </div>
                        <h4 class="control-sidebar-heading">Skins</h4>
                        <ul id="skins" class="list-unstyled clearfix">
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-blue" href="javascript:void(0);">
                                    <div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-light-blue"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p class="text-center no-margin">Blue</p>
                            </li>
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-black" href="javascript:void(0);">
                                    <div class="clearfix" style="box-shadow: 0 0 2px rgba(0,0,0,0.1)"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p class="text-center no-margin">Black</p>
                            </li>

                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-purple" href="javascript:void(0);">
                                    <div><span class="bg-purple-active" style="display:block; width: 20%; float: left; height: 7px;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-purple"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p class="text-center no-margin">Purple</p>
                            </li>
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-green" href="javascript:void(0);">
                                    <div><span class="bg-green-active" style="display:block; width: 20%; float: left; height: 7px;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-green"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p class="text-center no-margin">Green</p>
                            </li>
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-red" href="javascript:void(0);">
                                    <div><span class="bg-red-active" style="display:block; width: 20%; float: left; height: 7px;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-red"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p class="text-center no-margin">Red</p>
                            </li>
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-yellow" href="javascript:void(0);">
                                    <div><span class="bg-yellow-active" style="display:block; width: 20%; float: left; height: 7px;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-yellow"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p class="text-center no-margin">Yellow</p>
                            </li>
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-blue-light" href="javascript:void(0);">
                                    <div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-light-blue"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p style="font-size: 12px" class="text-center no-margin">Blue Light</p>
                            </li>

                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-black-light" href="javascript:void(0);">
                                    <div class="clearfix" style="box-shadow: 0 0 2px rgba(0,0,0,0.1)"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p style="font-size: 12px" class="text-center no-margin">Black Light</p>
                            </li>
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-purple-light" href="javascript:void(0);">
                                    <div><span class="bg-purple-active" style="display:block; width: 20%; float: left; height: 7px;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-purple"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a>
                                <p style="font-size: 12px" class="text-center no-margin">Purple Light</p>
                            </li>
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-green-light" href="javascript:void(0);">
                                    <div><span class="bg-green-active" style="display:block; width: 20%; float: left; height: 7px;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-green"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p style="font-size: 12px" class="text-center no-margin">Green Light</p>
                            </li>
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-red-light" href="javascript:void(0);">
                                    <div><span class="bg-red-active" style="display:block; width: 20%; float: left; height: 7px;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-red"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p style="font-size: 12px" class="text-center no-margin">Red Light</p>
                            </li>
                            <li style="float:left; width: 33.33333%; padding: 5px;">
                                <a class="clearfix full-opacity-hover" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" data-skin="skin-yellow-light" href="javascript:void(0);">
                                    <div><span class="bg-yellow-active" style="display:block; width: 20%; float: left; height: 7px;"></span><span style="display:block; width: 80%; float: left; height: 7px;" class="bg-yellow"></span></div>
                                    <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                                </a>
                                <p style="font-size: 12px;" class="text-center no-margin">Yellow Light</p>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.tab-pane -->

                <!-- Settings tab content -->
<!--                <div id="control-sidebar-settings-tab" class="tab-pane">-->
<!--                    <form method="post">-->
<!--                        <h3 class="control-sidebar-heading">General Settings</h3>-->
<!--                        <div class="form-group">-->
<!--                            <label class="control-sidebar-subheading">-->
<!--                                Report panel usage-->
<!--                                <input type="checkbox" checked="" class="pull-right">-->
<!--                            </label>-->
<!--                            <p>-->
<!--                                Some information about this general settings option-->
<!--                            </p>-->
<!--                        </div><!-- /.form-group -->
<!---->
<!--                        <div class="form-group">-->
<!--                            <label class="control-sidebar-subheading">-->
<!--                                Allow mail redirect-->
<!--                                <input type="checkbox" checked="" class="pull-right">-->
<!--                            </label>-->
<!--                            <p>-->
<!--                                Other sets of options are available-->
<!--                            </p>-->
<!--                        </div><!-- /.form-group -->
<!---->
<!--                        <div class="form-group">-->
<!--                            <label class="control-sidebar-subheading">-->
<!--                                Expose author name in posts-->
<!--                                <input type="checkbox" checked="" class="pull-right">-->
<!--                            </label>-->
<!--                            <p>-->
<!--                                Allow the user to show his name in blog posts-->
<!--                            </p>-->
<!--                        </div><!-- /.form-group -->
<!---->
<!--                        <h3 class="control-sidebar-heading">Chat Settings</h3>-->
<!---->
<!--                        <div class="form-group">-->
<!--                            <label class="control-sidebar-subheading">-->
<!--                                Show me as online-->
<!--                                <input type="checkbox" checked="" class="pull-right">-->
<!--                            </label>-->
<!--                        </div><!-- /.form-group -->
<!---->
<!--                        <div class="form-group">-->
<!--                            <label class="control-sidebar-subheading">-->
<!--                                Turn off notifications-->
<!--                                <input type="checkbox" class="pull-right">-->
<!--                            </label>-->
<!--                        </div><!-- /.form-group -->
<!---->
<!--                        <div class="form-group">-->
<!--                            <label class="control-sidebar-subheading">-->
<!--                                Delete chat history-->
<!--                                <a class="text-red pull-right" href="javascript::;"><i class="fa fa-trash-o"></i></a>-->
<!--                            </label>-->
<!--                        </div><!-- /.form-group -->
<!--                    </form>-->
<!--                </div><!-- /.tab-pane -->
                <?php

                $this->registerJs('

                            $(document).ready(function(){
                                var id_user = ' . \Yii::$app->user->identity->id . ';

                                $(\'.control-sidebar input:checkbox\').click(function(){

                                    var option =  $(this).attr("id");
                                    var value = 0;
                                    if ($(this).is(":checked"))
                                        var value = 1;

                                   // alert ($(this).attr("id")+" -- "+value);

                                    /* Fixed-header */
                                    if ((option == \'fixed-header\') && (value == 1))
                                        $(\'body\').addClass(\'fixed\');
                                    else if ((option == \'fixed-header\') && (value == 0))
                                        $(\'body\').removeClass(\'fixed\');

                                    /* Layout-boxed */
                                    if ((option == \'layout-boxed\') && (value == 1))
                                        $(\'body\').addClass(\'layout-boxed\');
                                    else if ((option == \'layout-boxed\') && (value == 0))
                                        $(\'body\').removeClass(\'layout-boxed\');

                                    /* Sidebar-collapse */
                                    if ((option == \'sidebar-collapse\') && (value == 1))
                                        $(\'body\').addClass(\'sidebar-collapse\');
                                    else if ((option == \'sidebar-collapse\') && (value == 0))
                                        $(\'body\').removeClass(\'sidebar-collapse\');

                                    /* control-sidebar-open */
                                    if ((option == \'control-sidebar-open\') && (value == 1))
                                        $(\'body\').addClass(\'control-sidebar-open\');
                                    else if ((option == \'control-sidebar-open\') && (value == 0))
                                        $(\'body\').removeClass(\'control-sidebar-open\');

                                    //console.log("dddd " + option + " --- " + value);

                                    $.ajax({
                                        type: \'POST\',
                                        url : \'/useroptions/updateajax/\',
                                        data : {userid: id_user, option: option, value: value},
                                        success : function() {
                                          $(this).closest(\'tr\').remove(); //or whatever html you use for displaying rows
                                        }
                                    });
                                });

                                $(\'ul#skins li a\').click(function(){
                                    var option = \'skin-color\';
                                    var value = $(this).attr("data-skin");
                                    //Added jquery.alterclasss.js
                                    $(\'body\').alterClass(\'skin-*\', value);

                                    $.ajax({
                                        type: \'POST\',
                                        url : \'/useroptions/updateajax/\',
                                        data : {userid: id_user, option: option, value: value},
                                        success : function() {
                                          $(this).closest(\'tr\').remove(); //or whatever html you use for displaying rows
                                        }
                                    });
                                });
                            });', \yii\web\View::POS_READY);

                ?>
            </div>
        </aside>

    </div>
        <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
