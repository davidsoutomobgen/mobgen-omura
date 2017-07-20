<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$this->beginContent('@common/mail/layouts/master.php'); ?>
<div class="container" style="padding-top:0">
	<div class="row-fluid">
		<div class="span12">
            Hello, <b><?php echo $user->first_name; ?></b>!<br><br>
            Welcome to the OTAShare.<br /><br>
            &nbsp;&nbsp;&nbsp;&nbsp;User: <?php echo $user->username; ?> <br />
            &nbsp;&nbsp;&nbsp;&nbsp;Email: <?php echo $user->email; ?> <br />
            &nbsp;&nbsp;&nbsp;&nbsp;Password: <?php echo $user->password; ?>'
            <br><br />Change your password in your profile.<br /><br />Greetings.<br />MOBGEN
		</div>
	</div>
</div><!-- content -->
<?php $this->endContent(); ?>
