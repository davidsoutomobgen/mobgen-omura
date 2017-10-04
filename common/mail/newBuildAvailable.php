<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$appLink = Yii::$app->params["FRONTEND"] . '/build/' . $model->buiHash;
?>

<p>Hello,</p>
<p>You have available a new version of the app "<?= $model->buiName;?>":</p>
<p><?= Html::a(Html::encode('Link to the app'), $appLink) ?></p>
<p>Regards,</p>
<p>MOBGEN</p>