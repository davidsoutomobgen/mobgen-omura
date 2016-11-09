
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\BuildsDownloaded;
?>

<article id="<?= $model->buiId; ?>" class="item <?= ($index & 1) ? '' : 'odd' ?> " data-key="<?= $model->buiHash; ?>" style=" margin: 0; padding:8px">

    <div class="itemdiv">
        <?php
        $logo = (empty($model->buiType))?'/images/apple.png':'/images/android.png';
        ?>
        <div class="left lateral_item"" style="padding: 6% 0; min-height:50px">
            <?= Html::img(Yii::getAlias('@web').$logo, ['width'=>'32']) ?>
        </div>
        <div class="left" style="width:70%;overflow: scroll">
            <p><?= Html::a(Html::encode($model->buiName), Yii::$app->params["FRONTEND"] . '/build/' . $model->buiHash, ['title' => $model->buiName]) ?></p>
            <p><small style="font-size:0.8em"><?= (!empty($model->buiBundleIdentifier)) ?  $model->buiBundleIdentifier : ' - ' ?></small></p>
            <p style="text-align:center"><?= date('d-m-Y H:i:s', $model->updated_at) ?></p>
        </div>
        <div class="left lateral_item icons">

                <span>
                <?php
                if ($model->buiFav == 1) {
                    $fav = '<i class="fa fa-star fa-x ' . $_SESSION['skin-color'] . '"></i>';
                }
                else {
                    $fav = '<i class="fa fa-star-o fa-x ' . $_SESSION['skin-color'] . '"></i>';
                }

                if ($model->buiVisibleClient == 1)
                    $visible = '<i class="fa fa-eye fa-x ' . $_SESSION['skin-color'] . '"></i>';
                else
                    $visible = '<i class="fa fa-eye-slash fa-x ' . $_SESSION['skin-color'] . '"></i>';
                
                echo $fav . '<br />' . BuildsDownloaded::getDownloads($model->buiId) . '<br/>'. $visible;

                //echo Html::img(Yii::getAlias('@web').$logo, ['width'=>'16']);
                //return Html::a($fav, $url, ['title' => $text, 'data-method' => 'post']);
                ?>
                </span>



        </div>
    </div>
    <div class="clear"></div>
</article>

