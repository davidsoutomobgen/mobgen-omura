
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\BuildsDownloaded;
use common\models\User;
use backend\models\Utils;
?>

<article id="<?= $model->id; ?>" class="item <?= ($index & 1) ? '' : 'odd' ?> " data-key="<?= $model->id; ?>" style=" margin: 0; padding:8px">

    <div class="itemdiv">
        <?php
        $userImg = (!empty($model->image))? '/files/mobgenners/'.$model->image : User::getImageUser($model->user);
        ?>
        <div class="left lateral_item"" style="padding: 6% 0; min-height:50px">
            <br />
            <?= Html::img(Yii::getAlias('@web').$userImg, ['width'=>'36']) ?>
        </div>
        <div class="left" style="width:70%;overflow: scroll">
            <p><b><?= $model->first_name . ' ' . $model->last_name; ?></b></p>
            <p><small><?= $model->email ?></small></p>
            <p><small><?= '<b>' . Yii::t('app', 'Phone:') . '</b> ' . (!empty($model->phone) ? $model->phone : '-'); ?></small></p>
            <p style="text-align:center"><?= Utils::getRolById($model->user0->role_id); ?></p>
        </div>
    </div>
    <div class="clear"></div>
</article>

