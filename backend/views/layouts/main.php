<?php
use backend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\bootstrap\Nav;
//use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\models\User;
use backend\models\UserOptions;
use backend\models\System;
use backend\models\Utils;

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
        if ($opt['type'] == 'integer')
            $session->set($opt['variable'], (int) $opt['value']);
        else if ($opt['type'] == 'string')
            $session->set($opt['variable'], (string) $opt['value']);
    }

    $user = User::getUserInfo();
}
else {
    return Yii::$app->response->redirect(Url::to(['/site/logout']));
}

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
            <a href="/site" class="logo">
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
                                <?php if (!empty($user->image)) : ?>
                                    <img src="<?= $user->image;?>" class="user-image" alt="User Image">
                                <?php else : ?>
                                    <img src="/files/user2-128x128.png" class="user-image" alt="User Image">
                                <?php endif; ?>

                                <span class="hidden-xs"><?php echo Yii::$app->user->identity->first_name.' '.Yii::$app->user->identity->last_name ; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?= $user->image;?>" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo Yii::$app->user->identity->first_name.' '.Yii::$app->user->identity->last_name ; ?>
                                        <small><?= Utils::getRolById($user->role_id);?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="/user/profile/<?= $userid;?>" class="btn btn-default btn-flat"><?= Yii::t('app', 'Profile'); ?></a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="/site/logout" class="btn btn-default btn-flat"  data-method="post">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <?php if (!\Yii::$app->devicedetect->isMobile()) { ?>
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <section class="sidebar">
                <?php $userId= \Yii::$app->user->identity->id; ?>

                <ul class="sidebar-menu">
                    <li class="header"><?= Yii::t('app', 'MAIN NAVIGATION'); ?></li>
                    <?php
                    if ($user->role_id == Yii::$app->params['CLIENT_ROLE']) { ?>
                        <li class="<?=($this->context->id == 'site') ? 'active' : '' ?> treeview">
                            <a href="/site">
                                <i class="fa fa-dashboard"></i> <span><?= Yii::t('app', 'Dashboard'); ?></span>
                            </a>
                        </li>
                        <li class="treeview <?=($this->context->id == 'otaprojects') ? 'active' : ' ' ?>">
                            <a href="/otaprojects">
                                <i class="fa fa-share-alt"></i> <span>OTA Share</span>
                            </a>
                        </li>
                        <li class="treeview <?=($this->context->id == 'user') ? 'active' : ' ' ?>">
                            <a href="/user/profile/<?=$userId?>">
                                <i class="fa fa-user"></i> <span>User</span>
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="<?=($this->context->id == 'site') ? 'active' : '' ?> treeview">
                            <a href="/site">
                                <i class="fa fa-dashboard"></i> <span><?= Yii::t('app', 'Dashboard'); ?></span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="/project">
                                <i class="fa fa-flask"></i> <span>Projects</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="/client">
                                <i class="fa fa-diamond"></i> <span>Clients</span>
                            </a>
                        </li>
                        <li class=" treeview">
                            <a href="/type">
                                <i class="fa fa-desktop"></i> <span>Project type</span>
                            </a>
                        </li>
                        <li class="treeview <?=($this->context->id == 'otaprojects') ? 'active' : ' ' ?>">
                            <a href="/otaprojects">
                                <i class="fa fa-share-alt"></i> <span>OTA Share</span>
                            </a>
                        </li>
                        <?php
                        if ($user->role_id < 11) {
                        ?>
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
                        <?php } ?>
                        <?php /* if ($user->role_id  == 1) { ?>
                            <li class="treeview <?=($this->context->id == 'devices') ? 'active' : ' ' ?>">
                                <a href="/devices">
                                    <i class="fa fa-tablet"></i> <span>Test Devices</span>
                                </a>
                            </li>
                        <?php } */ ?>
                        <?php  if ($user->role_id == 1 || $user->role_id== 12) { ?>
                            <li class="treeview <?=($this->context->id == 'mobgenners') ? 'active' : ' ' ?>">
                                <a href="/mobgenners">
                                    <i class="fa fa-circle-o"></i> <span><?= Yii::t('app', 'Mobgenners')?></span>
                                </a>
                            </li>
                        <?php
                        }
                    }

                    if (!\Yii::$app->devicedetect->isMobile()) {
                        if ($user->role_id  == 1) { ?>
                            <li class="<?=($this->context->id == 'useroptions') ||
                                          ($this->context->id == 'options') ||
                                          ($this->context->id == 'permissions') ||
                                          ($this->context->id == 'user') ||
                                          ($this->context->id == 'system')
                                           ? 'active' : '' ?> treeview">

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
                                    <!--
                                    <li class="<?=($this->context->id == 'useroptions') ? 'active' : ' ' ?>"><a href="/useroptions"><i class="fa fa-paint-brush"></i> Visual Options</a></li>
                                    <li class="<?=($this->context->id == 'options') ? 'active' : ' ' ?>"><a href="/options"><i class="fa fa-plus-square-o"></i>Parameters Visual Options</a></li>
                                    <li class="<?=($this->context->id == 'permissions') ? 'active' : ' ' ?>"><a href="/permissions"><i class="fa fa-shield"></i>Permissions</a></li>
                                    <li class="<?=($this->context->id == 'user') ? 'active' : ' ' ?>"><a href="/user"><i class="fa fa-users"></i>Users</a></li>
                                    -->
                                    <li class="<?=($this->context->id == 'system') ? 'active' : ' ' ?>"><a href="/system"><i class="fa fa-copyright"></i>System</a></li>
                                </ul>
                            </li>
                        <?php } ?>
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
                <b><?= Yii::t('app', 'Version'); ?></b> <?=System::getVersion();?>
            </div>
            <strong>Copyright &copy; <?=date('Y'); ?> <a href="http://www.mobgen.com">MOBGEN</a>.</strong> All rights reserved.
        </footer>
        <?php if (!\Yii::$app->devicedetect->isMobile()) { ?>
            <aside class="control-sidebar control-sidebar-dark">
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
                                    <input id="sidebar-collapse" type="checkbox" class="pull-right" data-layout="sidebar-collapse" <?=((isset($_SESSION['sidebar-collapse'])) && ($_SESSION['sidebar-collapse'] == 1 )) ? 'CHECKED' : '&bnsp;';?>> Toggle Sidebar</label>
                                <p>Toggle the left sidebar's state (open or collapse)</p>
                            </div>
                        </div>
                    </div><!-- /.tab-pane -->
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
                                            url : \'/useroptions/updateajax\',
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
        <?php } ?>
    </div>
        <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
