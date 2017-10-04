<html>
<head>
  <title><?=$subject;?></title>
</head>
<body>
    <h2>Project Name: <?=project->name;?> </h2>
    <p><strong>Name:</strong> <?=$build->buiName;?> </p>
    <p><strong>Build Number:</strong> <?=$build->buiBuildNum;?> </p>
    <p><strong>BuildType:</strong> <?=$build->buiBuildType;?> </p>
    <p><strong>Type:</strong> <?=$appType;?> </p>
    <p><strong>Version:</strong> <?=$build->buiVersion;?> </p>
    <p><strong>Change Log:</strong> <?=$build->buiChangeLog;?> </p>
    <p>A new build for <?=$project->proName;?> - <?=$build->buiBuildType;?> has been uploaded to MobGen OTA Share: <?=$build->buiVersion;?></p>
    <p><a href="<?=$url;?>">Click here to install it</a></p>
  <?=if $qrcode;?>
  <p><img src="<?=$qrcode;?>" /></p>
  <?=/if;?>
</body>
</html>
