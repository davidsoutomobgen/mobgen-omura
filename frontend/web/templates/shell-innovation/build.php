<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>MobGen OTAShare - ShellInnovation</title>
<link type="text/css" rel="stylesheet" href="/templates/shell-innovation/style.css" />
<meta name = "viewport" content = "width = 320, user-scalable = no">
</head>
<body>
<div id="content">
<div id="header"><h1>MobGen<br />OTA Install</h1></div>
<div><h1><?=$buildata->buiName?></h1></div>
<div><h2><?=$buildata->buiVersion?><?=(!empty($buildata->buiBuildType)) ? '-'.$buildata->buiBuildType : ''; ?></h2></div>
<div><div id="install"><a href="<?=$url?>">Install</a></div></div>
<div><h2>Changelog</h2></div>
<div>
</div>
</div>
</body>
</html>
