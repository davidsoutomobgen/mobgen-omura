<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>MobGen OTAShare - <?=$buildata->buiName?></title>
<link type="text/css" rel="stylesheet" href="/templates/whoiswho_ron/style.css" />
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
		<img src="/templates/whoiswho_ron/header.png" />
	</div>
	<div id="content">
			<h1>MobGen OTA Install</h1>
		<div>
			<p>Name: <?=$buildata->buiName?></p>
		</div>
		<div id="installBox">
			<div id="install">
				<a href="/build/download/<?=$buildata->buiId;?>"><img src="/templates/default/img/bttn_up.png" /></a>
			</div>
		</div>
	</div>
	<div id="footer">

	</div>
</div>
</body>
</html>