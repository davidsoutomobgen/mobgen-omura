<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>MobGen OTAShare - Motorist Hybrid POC</title>
    <link type="text/css" rel="stylesheet" href="/templates/james-beta-user/style.css" />
    <meta name = "viewport" content = "width=device-width,initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
    <script>
        setTimeout(function () {
            window.scrollTo(0, 1);
        }, 1000);
    </script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-82473334-1', 'auto', 'testTracker', {testInfo: 'getReckt'});
        ga('testTracker.send', 'pageview');

    </script>

</head>
<body>
<div id="container">
    <div id="header">
        <img src="/templates/james-beta-user/images/Logo_blauw.png" width="240" />
        <img id="dots" src="/templates/james-beta-user/images/otashare_mobgen_dots@2x.png" width="240" />
    </div>
    <div id="content">
        <h1>MobGen OTA Install</h1>
        <div>
            <div class="lbl">Name:</div> <span class="bltxt">KNAB - Beta <?php echo $buildata->buiName; ?></span>
        </div>
        <div style="margin-top:10px;">
            <div class="lbl">Version:</div> <span class="bltxt">Beta - <?php echo $buildata->buiVersion; ?>

</span>
        </div>
        <div id="installBox">
            <div id="install">
                <a href="<?php echo "/build/download/$buildata->buiHash"; ?>">Download</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    var urls = {
        "android": "TODO: ADD YOUR ANDROID APP URL HERE",
        "ios": "TODO: ADD YOUR IOS APP URL HERE - See Participants -> Waiting List Page",
    };

    var platform = navigator.userAgent ? "android" : "ios";

    function detectmob() {
        if( navigator.userAgent.match(/Android/i)
            || navigator.userAgent.match(/iPhone/i)
        ){
            return true;
        }
        else {
            return false;
        }
    }

    $('#install').click(function(event){
        alert(navigator.platform+", "+detectmob());
        ga('testTracker.send', 'pageview', {testInfo: 'WHUT?!'});
        ga('testTracker.send', {
            hitType: 'event',
            eventCategory: 'Download',
            eventAction: 'Click',
            "android": "TODO: ADD YOUR ANDROID APP URL HERE",
            "ios": "TODO: ADD YOUR IOS APP URL HERE - See Participants -> Waiting List Page",
        };

        var platform = navigator.userAgent ? "android" : "ios";

        function detectmob() {
            if( navigator.userAgent.match(/Android/i)
                || navigator.userAgent.match(/iPhone/i)
            ){
                return true;
            }
            else {
                return false;
            }
        }

        $('#install').click(function(event){
            alert(navigator.platform+", "+detectmob());
            ga('testTracker.send', 'pageview', {testInfo: 'WHUT?!'});
            ga('testTracker.send', {
                hitType: 'event',
                eventCategory: 'Download',
                eventAction: 'Click',
                eventLabel: 'Download App'
            });
        });
</script>
</body>
</html>