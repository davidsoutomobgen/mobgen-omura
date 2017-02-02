<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>MobGen OTAShare - <?=$buildata->buiName?></title>
	<link type="text/css" rel="stylesheet" href="/templates/default/css/style.css" />
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
<meta name = "viewport" content = "width=device-width,initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
<script>
setTimeout(function () {
	  window.scrollTo(0, 1);
	}, 1000);
</script>
</head>
<body>
<div class="wrap">
	<div class="grid-container">
		<div class="site-logo">
			<h1 class="logo">
				<a href="https://mobgen.com/" title="MOBGEN" rel="home">
					<img src="https://mobgen.com/wp-content/themes/brooklyn/images/mobgen_logo_top_black.png" alt="MOBGEN logo">
				</a>
			</h1>
		</div>
	</div>

	<div class="container">
		<div class="content-wrapper" style="text-align:center">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>OTAShare Install</h1>
			</section>

			<!-- Main content -->
			<section class="content" >
				<p class="lbl">Name: <span class="bltxt"><?=$buildata->buiName?></span></p>
				<p class="lbl">
					Version: <span class="bltxt"><?=$buildata->buiVersion?></span>
				</p>

				<div id="installBox">
					<div id="install">
						<?php if ($buildata->buiType) { ?>
							<a href="/build/download/<?=$buildata->buiHash;?>"><i class="fa fa-cloud-download fa-5x android" aria-hidden="true"></i></a>
						<?php } else { ?>
							<a href="itms-services://?action=download-manifest&amp;url=<?php echo Yii::$app->params["FRONTEND"] . '/build/ipa/' . $buildata->buiHash . '/' . $buildata->buiSafename . '/plist' ?>"><i class="fa fa-cloud-download fa-5x ios" aria-hidden="true"></i></a>
						<?php } ?>
					</div>
				</div>
				<div id="changelog">

					<?php
					if (empty($buildata->buiChangeLog))
						echo '<p class="lbl">Changelog: <span class="bltxt">-</span></p>';
					else
						echo '<p class="lbl">Changelog:</p><p class="changelogTxt">'.$buildata->buiChangeLog.'</p>'
					?>

				</div>
				<?php if ($mobile) { ?>
					<hr />
					<div id="qrImage">
						<img src="/build/qrcode/<?php echo $buildata->buiHash;?>" />
					</div>
				<?php } ?>
			</section>
		</div>
	</div>
</div>
<footer class="footer">
	<div class="container">
		<p class="pull-left"></p>
		<p class="pull-right">&copy; Copyright MOBGEN, mobile solution specialist since 2009</p>
	</div>
</footer>


</body>
</html>
