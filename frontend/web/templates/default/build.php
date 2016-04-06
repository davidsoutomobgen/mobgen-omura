<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>MobGen OTAShare - <?=$buildata->buiName?></title>
<link type="text/css" rel="stylesheet" href="/templates/default/css/style.css" />
<meta name = "viewport" content = "width=device-width,initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
<script>
setTimeout(function () {
	  window.scrollTo(0, 1);
	}, 1000);
</script>
</head>
<body>
<div id="container">
	<div id="header">
		<img src="/templates/default/img/otashare_mobgen_logo@2x.png" width="240" />
		<img id="dots" src="/templates/default/img/otashare_mobgen_dots@2x.png" width="240" />
	</div>
	<div id="content">
			<h1>MobGen OTA Install</h1>
		<div>
			<div class="lbl">Name:</div> <span class="bltxt"><?=$buildata->buiName?></span>
		</div>
		<div style="margin-top:10px;">
			<div class="lbl">Version:</div> <span class="bltxt"><?=$buildata->buiVersion?><?=(!empty($buildata->buiBuildType) ? '-'.$buildata->buiBuildType : ''); ?></span>
		</div>
		<div id="installBox">
			<div id="install">
				<a href="/build/download/<?=$buildata->buiId;?>">Install</a>
			</div>
		</div>
		<div id="changelog">
			<p>Changelog:</p>
		</div>
		<div>
			<p>
				<?=$buildata->buiChangeLog?>
			</p>
		</div>
	</div>
	<!-- <div id="footer">

	</div> -->
</div>
</body>
</html>