<!DOCTYPE html>
<html lang="en-EN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>MobGen OTA - {{$project.proName}}</title>
    <!-- Meta Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/web/templates/default/css/framework.css" type="text/css" media="all">
    <link rel="stylesheet" href="/web/templates/default/css/icons.css" type="text/css" media="all">
    <link rel="stylesheet" href="/web/templates/default/css/new_style.css" type="text/css" media="all">
    <link rel="stylesheet" href="/web/templates/default/css/font-awesome.css" type="text/css" media="all">

    <script type="text/javascript" src="/web/templates/default/js/jquery.min.js"></script>
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
                        {{if $active_select == 1}}
                            <form id="form_buildtype" action="#" method="POST">
                                {{assign var=buildTypes value=","|explode:$project.proBuildTypes}}
                                <select name="proBuildType" onchange="jQuery('#form_buildtype').submit();">
                                    <option value="all" >See all</option>
                                    {{foreach from=$buildTypes item=j}}
                                        {{if ($j|@trim) == ($proBuildTypeSelect|@trim)}}
                                            <option value="{{$j|@trim}}" selected="selected" >{{$j|@trim}}</option>
                                        {{else}}
                                            <option value="{{$j|@trim}}" >{{$j|@trim}}</option>
                                        {{/if}}
                                    {{/foreach}}
                                </select>
                            </form>
                        {{/if}}


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
                            {{$project.proName}} - Builds
                        </h1>
                        <!-- End Logo -->
                    </div>
                </div><!-- .wrapper -->
            </header><!-- #masthead -->

            <section id="content" role="main" class="clearfix animated">
                <div class="wrapper">
                    {{if $no_builds == 0}}
                    <p style="text-align: center">No results for Build type {{$proBuildTypeSelect}}</p>
                    {{else}}
                    <section class="home-section latest-posts">
                        <div class="grids masonry-layout entries masonry">

                            {{foreach from=$builds item=i}}
                            <article  class=" post type-post status-publish format-standard  grid-4 ">
                                <!-- <a href="/build/{{$i.buiHash}}/{{$i.buiSafename}}" class="button">Go Build</a> -->
                                {{if $i.buiDeviceOS == 1 }}
                                    <a href="/build/{{$i.buiHash}}/{{$i.buiSafename}}/download/{{$i.buiSafename}}.apk" class="button">Install</a>
                                    {{assign var="build_type" value="Android"}}
                                {{else}}
                                    <a href="itms-services://?action=download-manifest&url={{$currentDomain}}/build/{{$i.buiHash}}/{{$i.buiSafename}}/plist" class="button">Install</a>
                                    {{assign var="build_type" value="iOS"}}
                                {{/if}}

                                <a title="Send email with Build link"
                                   href="mailto:?subject=A new build for {{$project.proName}} - has been uploaded to Mobgen OTA&body=Project: {{$project.proName}}%0D%0AName: {{$i.buiName}} %0D%0AType: {{$build_type}}%0D%0ABuild Type: {{$build_types[$i.buiType]}}%0D%0AVersion: {{$i.buiVersion}}%0D%0AChange Log: {{$i.buiChangeLog}}%0D%0AClick here to install it <{{$currentDomain}}/build/{{$i.buiHash}}/{{$i.buiSafename}}>">
                                    <i class="fa fa-envelope fa-2x"></i>
                                </a>



                                <header class="entry-header">
                                    <div class="entry-meta">
                                        <span class="entry-category"><span class="label">Version:</span> {{$i.buiVersion}}</span>
                                        <span class="entry-date"> {{$i.buiCreated}}</span>
                                    </div>
                                    <h2 class="entry-title">
                                        {{$i.buiName}}
                                    </h2>
                                </header>
                                <div class="watermark_div">
                                    <div class="watermark {{$i.buiBuildType|trim|lower}}" title="{{$i.buiBuildType|trim|upper}}">
                                        {{$i.buiBuildType|trim|truncate:3:'':true:false|upper}}
                                    </div>
                                </div>
                                <div class="entry-summary">
                                    {{if $i.buiDeviceOS == 1 }}
                                        <i class="fa fa-android fa-4x" style="color:#A4C639"></i>
                                    {{else}}
                                        <i class="fa fa-apple fa-4x" style="color:#ACB8BE"></i>
                                    {{/if}}

                                    <p><span class="label">Bundle id:</span> {{$i.buiBundleIdentifier}}</p>
                                    <p><span class="label">Distribution:</span> {{$i.buiType}} - {{$distribution_types[$i.buiDistributionType]}} ({{$build_types[$i.buiType]}})</p>
                                    <p><span class="label">Build link: </span>{{$currentDomain}}/build/{{$i.buiHash}}/{{$i.buiSafename}}</p>
                                </div>
                            </article>
                            {{/foreach}}
                        </div>
                        {{if $number_pages > 1}}
                            <div class="pagination">
                                <div class="pagination">
                                    <ul class="page-numbers">
                                        {{if $actual_page > 2}}
                                            <li><a class="next page-numbers" href="{{$url}}/page/1/{{$proBuildTypeSelect|@trim}}"><i class="icon-chevron-left"></i></a></li>
                                        {{/if}}
                                        {{if $actual_page != 1}}
                                            <li><a class="next page-numbers" href="{{$url}}/page/{{$actual_page-1}}/{{$proBuildTypeSelect|@trim}}">{{$actual_page-1}}</a></li>
                                        {{/if}}
                                        <li><span class="page-numbers  current">{{$actual_page}}</span></li>
                                        {{if $actual_page != $number_pages}}
                                            <li><a class="next page-numbers" href="{{$url}}/page/{{$actual_page+1}}/{{$proBuildTypeSelect|@trim}}">{{$actual_page+1}}</a></li>
                                        {{/if}}
                                        {{if $actual_page+1 < $number_pages}}
                                            <li><a class="next page-numbers" href="{{$url}}/page/{{$number_pages}}/{{$proBuildTypeSelect|@trim}}"><i class="icon-chevron-right"></i></a></li>
                                        {{/if}}
                                    </ul>
                                </div>
                            </div>
                        {{/if}}
                    </section><!-- Latest Posts -->
                    {{/if}}
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

    <script type="text/javascript" src="/web/templates/default/js/jquery.assets.js"></script>
    <script type="text/javascript" src="/web/templates/default/js/jquery.custom.js"></script>
</body>
</html>