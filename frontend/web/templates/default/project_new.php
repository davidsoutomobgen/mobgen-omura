<!DOCTYPE html>
<html lang="en-EN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>MobGen OTA - <?= $project->name;?></title>
    <!-- Meta Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/templates/default/css/framework.css" type="text/css" media="all">
    <link rel="stylesheet" href="/templates/default/css/icons.css" type="text/css" media="all">
    <link rel="stylesheet" href="/templates/default/css/new_style.css" type="text/css" media="all">
    <link rel="stylesheet" href="/templates/default/css/font-awesome.css" type="text/css" media="all">

    <script type="text/javascript" src="/templates/default/js/jquery.min.js"></script>
    <script type="text/javascript">try{jQuery.noConflict();}catch(e){};</script>

</head>
<body class="project">
    <div id="outer-wrap">
        <div id="inner-wrap">

            <div id="pageslide">
                <a id="close-pageslide" href="#top"><i class="icon-remove-sign"></i></a>
            </div>
            <!-- Sidebar in Mobile View -->

            <header id="masthead" role="banner" class="clearfix">

                <div class="top-strip">
                    <div class="wrapper clearfix">

                       <!--
                        <form method="get" id="searchform" action="#" role="search">
                            <!--
                            <input name="s" id="s" value="Search" onfocus="if(this.value=='Search')this.value='';" onblur="if(this.value=='')this.value='Search';" type="text">
                            <button type="submit">
                                <i class="icon-search"></i>
                            </button>
                        </form>
                        -->
                        <?php
                        //echo '<pre>'; print_r($project->otaProjectsBuildtypes); echo '</pre>'; die;
                        ?>
                        <?php if (!empty($buildtypes)) { ?>
                            <form id="form_buildtype" action="/project/uvn0opsmz7p4/shell-inside-energy" method="POST">                                                              
                                <select name="proBuildType" onchange="jQuery('#form_buildtype').submit();">
                                    <option value="all" >See all</option>
                                        <?php foreach ($buildtypes as $j=>$type) { ?>

                                        <?php
                                        //echo '<pre>'; print_r($type); echo '</pre>'; die;
                                        ?>
                                        <!--
                                        <?php //if ($j|@trim) == ($proBuildTypeSelect|@trim);?>
                                        -->
                                            <option value="<?= $j;?>" selected="selected" ><?=$type;?></option>
                                         
                                        <?php //else;?>
                                             <!--  <option value="<?= $j;?>" ><?= $type;?></option>-->
                                        <?php // if;?>
                                        
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                            </form>
                        <?php } ?>


                        <a id="open-pageslide" href="#pageslide"><i class="icon-menu"></i></a>
                        <nav class="secondary-menu">
                            <ul id="secondary-nav" class="menu">
                                <li class="menu-item">
                                    <a href="/?mod=admin&action=projects" >Projects</a>
                                </li>
                                <li class="menu-item">
                                    <a href="#">Builds</a>
                                </li>
                            </ul>
                        </nav>
                    </div><!-- .wrapper -->
                </div><!-- .top-strip -->



                <div class="wrapper">
                    <div id="branding">
                        <!-- Logo -->
                        <h1 clas="project_name">
                            <?= $project->name; ?> - Builds
                        </h1>
                        <!-- End Logo -->
                    </div>
                </div><!-- .wrapper -->
            </header><!-- #masthead -->

               <section id="content" role="main" class="clearfix animated">
                <div class="wrapper">

                    <?php //print_r($builds); ?>


                    <?php if (!isset($builds[0])) {  ?>
                        <p style="text-align: center">No results for Build type <?php $proBuildTypeSelect;?></p>
                    <?php } else { ?>
                    <section class="home-section latest-posts">
                        <div class="grids masonry-layout entries masonry">

                            <?php foreach ($builds as $i) { ?>
                            <article  class=" post type-post status-publish format-standard  grid-4 ">
                                <!-- <a href="/build/<?= $i->buiHash;?>/<?= $i->buiSafename;?>" class="button">Go Build</a> -->
                                <?php if ($i->buiDeviceOS == 1) { ?>
                                    <a href="/build/<?= $i->buiHash;?>/<?= $i->buiSafename;?>/download/<?= $i->buiSafename;?>.apk" class="button">Install</a>
                                    <?php $build_type = "Android";?>
                                <?php } else { ?>
                                    <a href="itms-services://?action=download-manifest&url=<?= Yii::$app->params['FRONTEND'];?>/build/<?= $i->buiHash;?>/<?= $i->buiSafename;?>/plist" class="button">Install</a>
                                    <?php $build_type = "iOS";?>
                                <?php } ?>

                                <a title="Send email with Build link"
                                   href="mailto:?subject=A new build for <?= $project->name;?> - has been uploaded to Mobgen OTA&body=Project: <?= $project->name;?>%0D%0AName: <?= $i->buiName;?> %0D%0AType: <?= $build_type;?>%0D%0ABuild Type: <?php //$build_types[$i->buiType];?>%0D%0AVersion: <?= $i->buiVersion;?>%0D%0AChange Log: <?= $i->buiChangeLog;?>%0D%0AClick here to install it <<?= Yii::$app->params['FRONTEND']; ?>/build/<?= $i->buiHash;?>/<?= $i->buiSafename;?>>">
                                    <i class="fa fa-envelope fa-2x"></i>
                                </a>

                                <header class="entry-header">
                                    <div class="entry-meta">
                                        <span class="entry-category"><span class="label">Version:</span> <?= $i->buiVersion;?></span>
                                        <span class="entry-date"> <?= $i->buiCreated;?></span>
                                    </div>
                                    <h2 class="entry-title">
                                        <?= $i->buiName;?>
                                        <?php if ($i->buiFav) { ?>
                                            <i class="fa fa-star"></i>
                                        <?php } ?>
                                    </h2>
                                </header>
                                <div class="watermark_div">
                                    <div class="watermark <?= strtolower(trim($i->buiBuildType));?>" title="<?= strtoupper(trim($i->buiBuildType));?>">
                                        <?= strtoupper(substr(trim($i->buiBuildType), 0, 3));?>
                                    </div>
                                </div>
                                <div class="entry-summary">
                                    <?php if ($i->buiDeviceOS == 1) { ?>
                                        <i class="fa fa-android fa-4x" style="color:#A4C639"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-apple fa-4x" style="color:#ACB8BE"></i>
                                    <?php } ?>

                                    <p><span class="label">Bundle id:</span> <?= $i->buiBundleIdentifier;?></p>
                                    <p><span class="label">Distribution:</span> <?= $i->buiType;?> (<?php //$build_types[$i->buiType];?>)</p>
                                    <p><span class="label">Build link: </span><?= Yii::$app->params['FRONTEND'];?>/build/<?= $i->buiHash;?>/<?= $i->buiSafename;?></p>
                                </div>
                            </article>
                            <?php } ?>
                        </div>                        
                    </section><!-- Latest Posts -->
                    <?php } ?>
                </div>
            </section>
            <footer id="footer" role="contentinfo" class="animated">
                <div class="footer-sidebar">
                    <div id="supplementary" class="wrapper clearfix columns col-3">
                        <div id="first" class="widget-area" role="complementary">
                            <div id="ti-about-site-2" class="widget widget_ti-about-site">
                                <h3>Otashare</h3>
                            </div>
                        </div><!-- #first .widget-area -->
                        <div id="second" class="widget-area" role="complementary">
                            <div class="widget">
                                <h3>&copy;2015 Mobgen</h3>
                            </div>
                        </div><!-- #second .widget-area -->
                    </div><!-- #supplementary -->
                </div>
                <div class="copyright">
                    <div class="wrapper">
                        <div class="grids">
                            <div class="grid-10">
                            </div>
                            <div class="grid-2">
                                <a href="#" class="back-top">Top of page<i class="icon-chevron-up"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer><!-- #footer -->
        </div><!-- #inner-wrap -->
    </div><!-- #outer-wrap -->          
    <script type="text/javascript" src="/templates/default/js/jquery.assets.js"></script>
    <script type="text/javascript" src="/templates/default/js/jquery.custom.js"></script>
</body>
</html>