<div class="client-view"  style="text-align:center">
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
